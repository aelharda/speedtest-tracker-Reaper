<?php

namespace App\Filament\Pages\Settings;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Pages\SettingsPage;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;

class Ispreaper extends SettingsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'tabler-download';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return true; // Adjust as needed
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getTitle(): string
    {
        return 'ISP Reaper';
    }

    public static function getNavigationLabel(): string
    {
        return 'ISP Reaper';
    }

    protected static string $settings = IspreaperSettings::class;

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            Grid::make([ 'default' => 1 ])->schema([
                Checkbox::make('enabled')
                    ->label('Enable ISP Reaper')
                    ->helperText('Periodically downloads a file from a URL and deletes it locally.'),

                Grid::make([ 'default' => 1 ])
                    ->hidden(fn ($get) => $get('enabled') !== true)
                    ->schema([
                        Fieldset::make('Download Settings')->schema([
                            TextInput::make('url')
                                ->label('File URL')
                                ->placeholder('https://example.com/largefile.iso')
                                ->maxLength(2000)
                                ->required()
                                ->url(),

                            TextInput::make('check_interval')
                                ->label('Check Interval (seconds)')
                                ->numeric()
                                ->minValue(30)
                                ->maxValue(3600)
                                ->default(300),

                            TextInput::make('download_timeout')
                                ->label('Download Timeout (seconds)')
                                ->numeric()
                                ->minValue(30)
                                ->maxValue(3600)
                                ->default(600),
                        ]),

                        Fieldset::make('Schedule Settings')->schema([
                            TimePicker::make('start_time')
                                ->label('Start Time')
                                ->required(),

                            TimePicker::make('end_time')
                                ->label('End Time')
                                ->required(),
                        ]),
                    ]),
            ]);
    }

    public function getSavedData(): array
    {
        return \App\Helpers\IspreaperHelper::getConfig();
    }

    public function saveData(array $data): void
    {
        \App\Helpers\IspreaperHelper::saveConfig($data);
    }
}