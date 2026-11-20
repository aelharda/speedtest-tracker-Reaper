<?php

namespace App\Helpers;

use App\Settings\IspreaperSettings;

class IspreaperHelper
{
    public static function getConfig(): array
    {
        $settings = app(IspreaperSettings::class);
        return [
            'enabled' => $settings->isEnabled(),
            'url' => $settings->getUrl(),
            'check_interval' => $settings->getCheckInterval(),
            'download_timeout' => $settings->getDownloadTimeout(),
            'start_time' => $settings->getStartTime(),
            'end_time' => $settings->getEndTime(),
        ];
    }

    public static function saveConfig(array $config): void
    {
        app(IspreaperSettings::class)->setConfig($config);
    }

    public static function getLastRunTime(): ?int
    {
        return Cache::get('isp_reaper_last_run');
    }

    public static function setLastRunTime(int $timestamp): void
    {
        Cache::put('isp_reaper_last_run', $timestamp, now()->addHour());
    }
}