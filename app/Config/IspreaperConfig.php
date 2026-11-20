<?php

namespace App\Config;

use Illuminate\Support\Facades\Env;

class IspreaperConfig
{
    public static function isEnabled(): bool
    {
        return Env::get('SPEEDTEST_TRACKER_ISP_REAPER_ENABLED', false) === true;
    }

    public static function getUrl(): ?string
    {
        $url = trim(Env::get('SPEEDTEST_TRACKER_ISP_REAPER_URL', ''));
        if ($url && (substr($url, 0, 5) === 'http:' || substr($url, 0, 6) === 'https:')) {
            return $url;
        }
        return null;
    }

    public static function getCheckInterval(): int
    {
        return (int) Env::get('SPEEDTEST_TRACKER_ISP_REAPER_CHECK_INTERVAL', 300);
    }

    public static function getDownloadTimeout(): int
    {
        return (int) Env::get('SPEEDTEST_TRACKER_ISP_REAPER_DOWNLOAD_TIMEOUT', 600);
    }

    public static function getStartHour(): int
    {
        return (int) Env::get('SPEEDTEST_TRACKER_ISP_REAPER_START_HOUR', 2);
    }

    public static function getStartMinute(): int
    {
        return (int) Env::get('SPEEDTEST_TRACKER_ISP_REAPER_START_MINUTE', 0);
    }

    public static function getEndHour(): int
    {
        return (int) Env::get('SPEEDTEST_TRACKER_ISP_REAPER_END_HOUR', 4);
    }

    public static function getEndMinute(): int
    {
        return (int) Env::get('SPEEDTEST_TRACKER_ISP_REAPER_END_MINUTE', 0);
    }

    public static function getTempDir(): string
    {
        return rtrim(Env::get('SPEEDTEST_TRACKER_ISP_REAPER_TEMP_DIR', sys_get_temp_dir()), '/\');
    }
}