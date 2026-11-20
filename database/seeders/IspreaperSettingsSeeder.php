<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\InvokableSeeder;

class IspreaperSettingsSeeder extends InvokableSeeder
{
    /**
     * Run the database seeds.
     */
    public function handle(): void
    {
        // Only seed if table exists
        if (! $this->tableExists('isp_reaper_settings')) {
            return;
        }

        \DB::table('isp_reaper_settings')->truncate();

        \DB::table('isp_reaper_settings')->insert([
            'enabled' => false,
            'url' => null,
            'check_interval' => 300,
            'download_timeout' => 600,
            'start_time' => '02:00:00',
            'end_time' => '04:00:00',
        ]);
    }

    private function tableExists(string $table): bool
    {
        return \Schema::hasTable($table);
    }
}