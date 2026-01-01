<?php

declare(strict_types=1);

namespace AmdadulHaq\Setting;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Setting::class, fn (): Setting => new Setting);

        $this->app->alias(Setting::class, 'setting');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/setting.php' => config_path('setting.php'),
            ], 'setting-laravel-config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_settings_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_settings_table.php'),
            ], 'setting-laravel-migrations');
        }

        Cache::macro('flushSettings', function () {
            $cacheKey = config('setting.cache_key', 'settings.cache');

            return Cache::forget($cacheKey);
        });
    }
}
