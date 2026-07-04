<?php

declare(strict_types=1);

use AmdadulHaq\Setting\Facades\Setting as SettingFacade;
use AmdadulHaq\Setting\Models\Setting;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Setting::query()->delete();
});

it('can set a string value', function (): void {
    setting()->set('app_name', 'Laravel');

    expect(setting()->get('app_name'))->toBe('Laravel');
});

it('can set an integer value', function (): void {
    setting()->set('max_users', 100);

    expect(setting()->get('max_users'))->toBe(100);
    expect(setting()->get('max_users'))->toBeInt();
});

it('can set a float value', function (): void {
    setting()->set('tax_rate', 15.5);

    expect(setting()->get('tax_rate'))->toBe(15.5);
    expect(setting()->get('tax_rate'))->toBeFloat();
});

it('can set a boolean value', function (): void {
    setting()->set('maintenance_mode', true);
    expect(setting()->get('maintenance_mode'))->toBeTrue();

    setting()->set('maintenance_mode', false);
    expect(setting()->get('maintenance_mode'))->toBeFalse();
});

it('can set an array value', function (): void {
    $data = ['foo' => 'bar', 'baz' => 'qux'];
    setting()->set('options', $data);

    expect(setting()->get('options'))->toBe($data);
    expect(setting()->get('options'))->toBeArray();
});

it('can set a JSON object value', function (): void {
    $data = ['foo' => 'bar', 'nested' => ['key' => 'value']];
    setting()->set('json_config', $data);

    expect(setting()->get('json_config'))->toBe($data);
});

it('can get a value with default when key does not exist', function (): void {
    expect(setting()->get('nonexistent', 'default'))->toBe('default');
    expect(setting()->get('nonexistent'))->toBeNull();
});

it('can check if a setting exists', function (): void {
    setting()->set('app_name', 'Laravel');

    expect(setting()->has('app_name'))->toBeTrue();
    expect(setting()->has('nonexistent'))->toBeFalse();
});

it('can remove a setting', function (): void {
    setting()->set('app_name', 'Laravel');
    expect(setting()->has('app_name'))->toBeTrue();

    setting()->remove('app_name');
    expect(setting()->has('app_name'))->toBeFalse();
});

it('can get all settings', function (): void {
    setting()->set('app_name', 'Laravel');
    setting()->set('max_users', 100);
    setting()->set('maintenance', false);

    $all = setting()->all();

    expect($all)->toHaveCount(3);
    expect($all->get('app_name'))->toBe('Laravel');
    expect($all->get('max_users'))->toBe(100);
    expect($all->get('maintenance'))->toBeFalse();
});

it('can set multiple settings at once', function (): void {
    setting()->setMultiple([
        'app_name' => 'Laravel',
        'max_users' => 100,
        'maintenance' => false,
    ]);

    expect(setting()->get('app_name'))->toBe('Laravel');
    expect(setting()->get('max_users'))->toBe(100);
    expect(setting()->get('maintenance'))->toBeFalse();
});

it('can get multiple settings at once', function (): void {
    setting()->set('app_name', 'Laravel');
    setting()->set('max_users', 100);
    setting()->set('maintenance', false);

    $settings = setting()->getMultiple(['app_name', 'max_users']);

    expect($settings)->toBe([
        'app_name' => 'Laravel',
        'max_users' => 100,
    ]);
});

it('can get multiple settings with default values', function (): void {
    setting()->set('app_name', 'Laravel');

    $settings = setting()->getMultiple(['app_name', 'nonexistent'], 'default');

    expect($settings)->toBe([
        'app_name' => 'Laravel',
        'nonexistent' => 'default',
    ]);
});

it('can update an existing setting', function (): void {
    setting()->set('app_name', 'Laravel');
    expect(setting()->get('app_name'))->toBe('Laravel');

    setting()->set('app_name', 'My App');
    expect(setting()->get('app_name'))->toBe('My App');

    assertDatabaseHas('settings', ['key' => 'app_name', 'value' => 'My App']);
});

it('returns the model when setting a value', function (): void {
    $result = setting()->set('app_name', 'Laravel');

    expect($result)->toBeInstanceOf(Setting::class);
});

it('flushes cache when setting a value', function (): void {
    config(['setting.cache_enabled' => true]);

    setting()->set('app_name', 'Laravel');
    setting()->get('app_name');

    setting()->set('app_name', 'Updated');

    expect(setting()->get('app_name'))->toBe('Updated');
});

it('flushes cache when removing a value', function (): void {
    config(['setting.cache_enabled' => true]);

    setting()->set('app_name', 'Laravel');
    setting()->get('app_name');

    setting()->remove('app_name');

    expect(setting()->get('app_name'))->toBeNull();
});

it('manually flushes cache', function (): void {
    config(['setting.cache_enabled' => true]);

    setting()->set('app_name', 'Laravel');
    $firstCall = setting()->get('app_name');

    setting()->set('app_name', 'Updated');

    expect(setting()->get('app_name'))->toBe('Updated');
});

it('works with facade', function (): void {
    SettingFacade::set('app_name', 'Laravel');

    expect(SettingFacade::get('app_name'))->toBe('Laravel');
    expect(SettingFacade::has('app_name'))->toBeTrue();

    SettingFacade::remove('app_name');
    expect(SettingFacade::has('app_name'))->toBeFalse();
});

it('returns singleton instance from helper', function (): void {
    $instance1 = setting();
    $instance2 = setting();

    expect($instance1)->toBe($instance2);
});

it('uses database when cache is disabled', function (): void {
    config(['setting.cache_enabled' => false]);

    setting()->set('app_name', 'Laravel');

    expect(setting()->get('app_name'))->toBe('Laravel');
});

it('handles null values', function (): void {
    setting()->set('nullable_field', null);

    expect(setting()->get('nullable_field'))->toBeNull();
});

it('handles empty string values', function (): void {
    setting()->set('empty_field', '');

    expect(setting()->get('empty_field'))->toBe('');
});

it('overwrites app config with stored settings', function (): void {
    config(['app.name' => 'Original']);

    setting()->set('app.name', 'Overwritten');
    setting()->overwriteConfig();

    expect(config('app.name'))->toBe('Overwritten');
});

it('overwrites only the given keys when restricted', function (): void {
    config(['app.name' => 'Original', 'app.env' => 'production']);

    setting()->set('app.name', 'Overwritten');
    setting()->set('app.env', 'staging');

    setting()->overwriteConfig(['app.name']);

    expect(config('app.name'))->toBe('Overwritten');
    expect(config('app.env'))->toBe('production');
});

it('overwrites nothing when no settings are stored', function (): void {
    config(['app.name' => 'Original']);

    setting()->overwriteConfig();

    expect(config('app.name'))->toBe('Original');
});

it('flushes cache via the Cache macro', function (): void {
    config(['setting.cache_enabled' => true]);

    setting()->set('app_name', 'Laravel');
    setting()->get('app_name');

    setting()->set('app_name', 'Updated');
    Cache::flushSettings();

    expect(setting()->get('app_name'))->toBe('Updated');
});

it('can check if a setting exists when cache is disabled', function (): void {
    config(['setting.cache_enabled' => false]);

    setting()->set('app_name', 'Laravel');

    expect(setting()->has('app_name'))->toBeTrue();
    expect(setting()->has('nonexistent'))->toBeFalse();
});

it('can get all settings when cache is disabled', function (): void {
    config(['setting.cache_enabled' => false]);

    setting()->set('app_name', 'Laravel');
    setting()->set('max_users', 100);

    $all = setting()->all();

    expect($all)->toHaveCount(2);
    expect($all->get('app_name'))->toBe('Laravel');
});

it('can get multiple settings when cache is disabled', function (): void {
    config(['setting.cache_enabled' => false]);

    setting()->set('app_name', 'Laravel');
    setting()->set('max_users', 100);

    $settings = setting()->getMultiple(['app_name', 'max_users', 'nonexistent'], 'default');

    expect($settings)->toBe([
        'app_name' => 'Laravel',
        'max_users' => 100,
        'nonexistent' => 'default',
    ]);
});

it('does not flush cache when setMultiple is used and cache is disabled', function (): void {
    config(['setting.cache_enabled' => false]);

    setting()->setMultiple(['app_name' => 'Laravel']);

    expect(setting()->get('app_name'))->toBe('Laravel');
});

it('returns false when removing a nonexistent key', function (): void {
    expect(setting()->remove('nonexistent'))->toBeFalse();
});

it('can set an object value', function (): void {
    $object = (object) ['foo' => 'bar'];

    setting()->set('object_setting', $object);

    expect(setting()->get('object_setting'))->toBe(['foo' => 'bar']);
});

it('does not throw when a stored value is a raw database null', function (): void {
    Setting::query()->create(['key' => 'raw_null', 'value' => null]);

    config(['setting.cache_enabled' => false]);

    expect(setting()->get('raw_null'))->toBeNull();
});

it('casts a leading-zero numeric string as an integer', function (): void {
    setting()->set('code', '0123');

    expect(setting()->get('code'))->toBe(123);
});
