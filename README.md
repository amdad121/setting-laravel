# Setting option for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdadulhaq/setting-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/setting-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/setting-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/setting-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/setting-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/setting-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdadulhaq/setting-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/setting-laravel)

A minimal and powerful settings package for Laravel with caching, type casting, and bulk operations.

## Features

- Simple key-value storage
- Automatic type casting (strings, integers, floats, booleans, arrays, JSON)
- Built-in caching for performance
- Bulk operations for setting/getting multiple values
- Facade support
- Configurable cache TTL
- Database storage

## Installation

You can install the package via composer:

```bash
composer require amdadulhaq/setting-laravel
```

### Requirements

- PHP 8.2, 8.3, 8.4, or 8.5
- Laravel 10, 11, or 12

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

## Configuration

The config file allows you to configure caching behavior:

```php
return [
    'cache_enabled' => env('SETTING_CACHE_ENABLED', true),
    'cache_key' => env('SETTING_CACHE_KEY', 'settings.cache'),
    'cache_ttl' => env('SETTING_CACHE_TTL', 60 * 60 * 24), // 24 hours
];
```

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
