# Setting Option for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdad121/setting-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/setting-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/setting-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/setting-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/setting-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/setting-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdad121/setting-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/setting-laravel)

This is a minimal setting option package for Laravel. You can easily use on your project.

## Installation

You can install the package via composer:

```bash
composer require amdad121/setting-laravel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="setting-laravel-migrations"
php artisan migrate
```

## Usage

```php
use AmdadulHaq\Setting\Models\Setting;

Setting::set('app_name', 'Laravel');

return Setting::get('name');
// Laravel

return Setting::remove('name');
// true
```

or more simple ways

```php
setting()->set('app_name', 'Laravel');

return setting()->get('app_name');
// Laravel

return setting()->remove('app_name');
// true
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
