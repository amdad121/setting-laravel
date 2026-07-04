<?php

declare(strict_types=1);

use AmdadulHaq\Setting\Models\Setting;
use AmdadulHaq\Setting\SettingServiceProvider;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    Setting::query()->delete();
});

it('does not overwrite config automatically when the feature is disabled', function (): void {
    config(['setting.overwrite_config' => false, 'app.name' => 'Original']);

    setting()->set('app.name', 'Overwritten');

    expect(config('app.name'))->toBe('Original');
});

it('registers the flushSettings cache macro', function (): void {
    expect(Cache::hasMacro('flushSettings'))->toBeTrue();
});

it('overwrites config automatically on boot when enabled', function (): void {
    config(['setting.overwrite_config' => true, 'app.name' => 'Original']);

    setting()->set('app.name', 'Overwritten');

    (new SettingServiceProvider(app()))->boot();

    expect(config('app.name'))->toBe('Overwritten');
});

it('restricts automatic config overwriting to configured keys', function (): void {
    config([
        'setting.overwrite_config' => true,
        'setting.overwrite_config_keys' => ['app.name'],
        'app.name' => 'Original',
        'app.env' => 'production',
    ]);

    setting()->set('app.name', 'Overwritten');
    setting()->set('app.env', 'staging');

    (new SettingServiceProvider(app()))->boot();

    expect(config('app.name'))->toBe('Overwritten');
    expect(config('app.env'))->toBe('production');
});
