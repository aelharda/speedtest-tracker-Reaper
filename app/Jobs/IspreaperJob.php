<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class IspreaperJob implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 300;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check env-based config first (for initial startup), then DB for runtime override
        if (! app(\App\Config\IspreaperConfig::class)->isEnabled()) {
            Log::info('[ISP Reaper] Job skipped: not enabled.');
            return;
        }

        $url = app(\App\Config\IspreaperConfig::class)->getUrl();
        if (!$url) {
            Log::info('[ISP Reaper] Job skipped: no URL configured.');
            Cache::forget('isp_reaper_last_run');
            return;
        }

        $interval = app(\App\Config\IspreaperConfig::class)->getCheckInterval();
        $timeout = app(\App\Config\IspreaperConfig::class)->getDownloadTimeout();
        $tempDir = app(\App\Config\IspreaperConfig::class)->getTempDir();

        Log::info("[ISP Reaper] Starting check for: {$url}");

        // Quick HEAD request to see if file exists
        $process = new Process(['curl', '-I', '--max-time', (string) 5, '--head', $url]);
        $process->run();

        if ($process->isSuccessful() && in_array((string) $process->getExitCode(), ['0', '304'])) {
            Log::info("[ISP Reaper] File available at: {$url}");

            // Download to temp file
            $ts = time();
            $tmpPath = sprintf('%s/speedtest_ispreaper_%d.tmp', $tempDir, $ts);

            Log::info("[ISP Reaper] Downloading to: {$tmpPath}");

            $dlProcess = new Process([
                'curl',
                '-o', $tmpPath,
                '--max-time', (string) $timeout,
                '--progress-bar',
                $url,
            ]);

            $dlProcess->run();

            if ($dlProcess->isSuccessful()) {
                $size = filesize($tmpPath);
                Log::info("[ISP Reaper] Download complete: {$size} bytes");

                // Delete immediately after success
                @unlink($tmpPath);
                Log::info('[ISP Reaper] Deleted local copy');

                // Record successful run
                \App\Helpers\IspreaperHelper::setLastRunTime(time());
            } else {
                Log::error("[ISP Reaper] Download failed: " . $dlProcess->getErrorOutput());
                @unlink($tmpPath);
            }
        } else {
            Log::info("[ISP Reaper] File not ready or unavailable at: {$url}");
        }

        // Sleep for next interval
        usleep($interval * 1_000_000);
    }
}