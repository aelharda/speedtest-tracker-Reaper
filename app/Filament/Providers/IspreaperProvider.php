<?php

namespace App\Filament\Providers;

use Filament\Schemas\SchemaManager;

class IspreaperProvider
{
    public static function registerWith(SchemaManager $manager): void
    {
        $manager->registerSchema(\App\Settings\IspreaperSettings::class);
    }
}