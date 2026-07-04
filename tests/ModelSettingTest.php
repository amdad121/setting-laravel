<?php

declare(strict_types=1);

use AmdadulHaq\Setting\Models\Setting;

beforeEach(function (): void {
    Setting::query()->delete();
});

it('can set a value via the model', function (): void {
    $setting = Setting::set('app_name', 'Laravel');

    expect($setting)->toBeInstanceOf(Setting::class);
    expect($setting->value)->toBe('Laravel');
});

it('can get a value via the model', function (): void {
    Setting::set('app_name', 'Laravel');

    expect(Setting::get('app_name'))->toBe('Laravel');
});

it('returns the default when the key is missing via the model', function (): void {
    expect(Setting::get('nonexistent', 'default'))->toBe('default');
    expect(Setting::get('nonexistent'))->toBeNull();
});

it('can remove a value via the model', function (): void {
    Setting::set('app_name', 'Laravel');

    expect(Setting::remove('app_name'))->toBeTrue();
    expect(Setting::get('app_name'))->toBeNull();
});

it('returns false when removing a nonexistent key via the model', function (): void {
    expect(Setting::remove('nonexistent'))->toBeFalse();
});

it('updates an existing value via the model', function (): void {
    Setting::set('app_name', 'Laravel');
    Setting::set('app_name', 'Updated');

    expect(Setting::get('app_name'))->toBe('Updated');
    expect(Setting::query()->where('key', 'app_name')->count())->toBe(1);
});
