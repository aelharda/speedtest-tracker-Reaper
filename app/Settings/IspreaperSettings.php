<?php

namespace App\Settings;

use Spatie\Settings\SettingsModel;

class IspreaperSettings extends SettingsModel
{
    protected string $table = 'isp_reaper_settings';

    public function isEnabled(): bool
    {
        return $this->get('enabled', false);
    }

    public function setEnabled(bool $value): self
    {
        $this->set('enabled', $value);
        return $this;
    }

    public function getUrl(): ?string
    {
        $url = $this->get('url');
        if ($url && (substr($url, 0, 5) === 'http:' || substr($url, 0, 6) === 'https:')) {
            return $url;
        }
        return null;
    }

    public function setUrl(?string $value): self
    {
        $this->set('url', $value);
        return $this;
    }

    public function getCheckInterval(): int
    {
        return (int) $this->get('check_interval', 300);
    }

    public function setCheckInterval(int $value): self
    {
        $this->set('check_interval', $value);
        return $this;
    }

    public function getDownloadTimeout(): int
    {
        return (int) $this->get('download_timeout', 600);
    }

    public function setDownloadTimeout(int $value): self
    {
        $this->set('download_timeout', $value);
        return $this;
    }

    public function getStartTime(): string
    {
        return $this->get('start_time', '02:00');
    }

    public function setStartTime(string $value): self
    {
        $this->set('start_time', $value);
        return $this;
    }

    public function getEndTime(): string
    {
        return $this->get('end_time', '04:00');
    }

    public function setEndTime(string $value): self
    {
        $this->set('end_time', $value);
        return $this;
    }
}