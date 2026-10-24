<?php

namespace Saivra\MeUi;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class MeUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'meui');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'meui');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations/2026_10_24_000000_create_meui_preferences_table.php' => database_path('migrations/2026_10_24_000000_create_meui_preferences_table.php'),
        ], 'meui-migrations');

        // Only enabled panels emit the link. filament:assets publishes the distribution CSS.
        FilamentAsset::register([
            Css::make('theme', __DIR__.'/../resources/css/theme.css')->loadedOnRequest(),
            Css::make('customization', __DIR__.'/../resources/css/customization.css')->loadedOnRequest(),
        ], package: 'saivra/meui-theme');
    }
}
