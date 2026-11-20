<?php

namespace App\Console\Commands;

use App\Config\IspreaperConfig;
use Illuminate\Console\Command;

class RunIspreaper extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'isp:reaper';

    /**
     * The console command description.
     */
    protected $description = 'Run ISP Reaper download-and-delete job between configured times.';

    /**
     * Execute the command.
     */
    public function handle(): int
    {
        if (! IspreaperConfig::isEnabled()) {
            $this->info('[ISP Reaper] Not enabled. Skipping.');
            return self::SUCCESS;
        }

        $startHour = IspreaperConfig::getStartHour();
        $startMinute = IspreaperConfig::getStartMinute();
        $endHour = IspreaperConfig::getEndHour();
        $endMinute = IspreaperConfig::getEndMinute();

        $now = new \DateTime('now');
        $currentHour = (int) $now->format('G');
        $currentMinute = (int) $now->format('i');

        // Determine if we are within the run window
        $inWindow = false;
        if ($startHour < $endHour) {
            $inWindow = ($currentHour >= $startHour && $currentHour < $endHour);
        } else {
            // Handles cases like 22:00 to 04:00
            $inWindow = ($currentHour >= $startHour || $currentHour < $endHour);
        }

        if (!$inWindow) {
            $this->info("[ISP Reaper] Outside run window ({$startHour}:{$startMinute} - {$endHour}:{$endMinute}). Skipping.");
            return self::SUCCESS;
        }

        // Run a single execution of the job
        $runner = new \App\Jobs\IspreaperJob();
        $runner->handle();

        return self::SUCCESS;
    }
}