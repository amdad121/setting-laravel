# Setting option for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdadulhaq/setting-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/setting-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/setting-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/setting-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/setting-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/setting-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdadulhaq/setting-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/setting-laravel)

A minimal and powerful settings package for Laravel with caching, type casting, and bulk operations.

## Features

- Simple key-value storage backed by a database table
- Automatic type casting (strings, integers, floats, booleans, `null`, arrays, JSON)
- Built-in caching, with a configurable key and TTL
- Bulk operations for setting/getting multiple values at once
- `Cache::flushSettings()` macro and manual cache flushing
- Facade and `setting()` helper function support
- Optional runtime overwriting of `config()` values from stored settings,
  restrictable to specific keys, automatic or manual
- Direct access to the underlying Eloquent model for raw, uncached reads/writes

## Installation

You can install the package via composer:

```bash
composer require amdadulhaq/setting-laravel
```

### Requirements

- PHP 8.2, 8.3, 8.4, or 8.5
- Laravel 10, 11, 12, or 13

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="setting-laravel-migrations"
php artisan migrate
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="setting-laravel-config"
```

## Usage

### Basic Usage

```php
use AmdadulHaq\Setting\Facades\Setting;

Setting::set('app_name', 'Laravel');

return Setting::get('app_name');
// Laravel

Setting::remove('app_name');
// true

Setting::has('app_name');
// false
```

### Helper Function

```php
setting()->set('app_name', 'Laravel');

return setting()->get('app_name');
// Laravel

return setting()->remove('app_name');
// true
```

### Type Casting

The package automatically casts values to their appropriate types:

```php
setting()->set('string_value', 'Hello');
setting()->set('integer_value', 100);
setting()->set('float_value', 15.5);
setting()->set('boolean_value', true);
setting()->set('array_value', ['foo' => 'bar']);
setting()->set('json_value', ['nested' => ['key' => 'value']]);

setting()->get('integer_value'); // Returns: 100 (int)
setting()->get('boolean_value'); // Returns: true (bool)
setting()->get('array_value');   // Returns: ['foo' => 'bar'] (array)
```

### Default Values

```php
setting()->get('nonexistent_key', 'default_value');
// default_value
```

### Bulk Operations

```php
// Set multiple settings at once
setting()->setMultiple([
    'app_name' => 'Laravel',
    'max_users' => 100,
    'maintenance_mode' => false,
]);

// Get multiple settings at once
$settings = setting()->getMultiple(['app_name', 'max_users']);
// ['app_name' => 'Laravel', 'max_users' => 100]
```

### Get All Settings

```php
$allSettings = setting()->all();
// Returns a Collection of all settings
```

### Cache Management

```php
// Manually flush the cache
setting()->flushCache();
```

Under the hood this uses a `Cache::flushSettings()` macro registered by the
package, which you can also call directly:

```php
Cache::flushSettings();
```

### Overwrite App Config

Store a setting using a key that matches a config path (dot notation) to have
it overwrite `config()` at runtime — useful for changing things like mail
settings, feature limits, or third-party keys without redeploying:

```php
setting()->set('mail.from.address', 'hello@example.com');

setting()->overwriteConfig();

config('mail.from.address');
// hello@example.com
```

Restrict which keys get applied by passing an explicit list:

```php
setting()->overwriteConfig(['mail.from.address', 'app.name']);
```

Enable `SETTING_OVERWRITE_CONFIG=true` in your `.env` to have this applied
automatically on every request boot (once the `settings` table exists):

```env
SETTING_OVERWRITE_CONFIG=true
```

To restrict the automatic, boot-time overwrite to specific keys, publish the
config file and set `overwrite_config_keys`. Leave it empty to apply every
stored setting that matches a config path:

```php
// config/setting.php
'overwrite_config_keys' => ['mail.from.address', 'app.name'],
```

### Using the Model Directly

The underlying Eloquent model also exposes simple static helpers, if you'd
rather bypass caching and type casting:

```php
use AmdadulHaq\Setting\Models\Setting;

Setting::set('app_name', 'Laravel');   // stores the raw string
Setting::get('app_name');              // 'Laravel' (always string|null)
Setting::remove('app_name');           // true
```

## Configuration

Publish the config file to customize caching behavior and config overwriting:

```bash
php artisan vendor:publish --tag="setting-laravel-config"
```

```php
return [
    // Whether settings are cached, and for how long.
    'cache_enabled' => env('SETTING_CACHE_ENABLED', true),
    'cache_key' => env('SETTING_CACHE_KEY', 'settings.cache'),
    'cache_ttl' => env('SETTING_CACHE_TTL', 60 * 60 * 24), // 24 hours

    // Automatically overwrite config() with matching stored settings on boot.
    'overwrite_config' => env('SETTING_OVERWRITE_CONFIG', false),

    // Restrict automatic overwriting to these keys; empty applies all.
    'overwrite_config_keys' => [],
];
```

| Option | Env variable | Default | Description |
| --- | --- | --- | --- |
| `cache_enabled` | `SETTING_CACHE_ENABLED` | `true` | Cache settings in the configured cache store. |
| `cache_key` | `SETTING_CACHE_KEY` | `settings.cache` | Cache key used to store all settings. |
| `cache_ttl` | `SETTING_CACHE_TTL` | `86400` | Cache lifetime in seconds. |
| `overwrite_config` | `SETTING_OVERWRITE_CONFIG` | `false` | Automatically apply stored settings onto `config()` on every boot. |
| `overwrite_config_keys` | — | `[]` | Limit automatic overwriting to these setting keys (array, set in the published config file). |

## API Reference

| Method | Description |
| --- | --- |
| `get(string $key, mixed $default = null): mixed` | Get a setting, type-cast to its original value. |
| `set(string $key, mixed $value): Setting` | Create or update a setting, returning the model. |
| `remove(string $key): bool` | Delete a setting; `true` if a row was deleted. |
| `has(string $key): bool` | Check whether a setting exists. |
| `all(): Collection` | Get every setting as a `key => value` collection. |
| `setMultiple(array $settings): void` | Create or update several settings at once. |
| `getMultiple(array $keys, mixed $default = null): array` | Get several settings at once, as `key => value`. |
| `flushCache(): void` | Manually forget the cached settings collection. |
| `overwriteConfig(?array $keys = null): void` | Apply stored settings onto `config()`, optionally restricted to `$keys`. |

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Amdadul Haq](https://github.com/amdad121)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
