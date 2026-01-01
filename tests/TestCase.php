<?php

declare(strict_types=1);

namespace AmdadulHaq\Setting\Tests;

use AmdadulHaq\Setting\SettingServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SettingServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $app->make(Repository::class)->set('setting.cache_enabled', true);
        $app->make(Repository::class)->set('setting.cache_key', 'settings.cache');
        $app->make(Repository::class)->set('setting.cache_ttl', 60 * 60 * 24);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
