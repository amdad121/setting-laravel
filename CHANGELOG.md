# Changelog

All notable changes to `setting-laravel` will be documented in this file.

## v2.0.0 - 2025-01-01

### Breaking Changes
- Minimum PHP version increased from 8.1 to 8.2
- Removed `spatie/laravel-package-tools` dependency
- Changed base ServiceProvider from `PackageServiceProvider` to `ServiceProvider`

### Added
- Automatic type casting (strings, integers, floats, booleans, arrays, JSON)
- Built-in caching support for improved performance
- Bulk operations: `setMultiple()`, `getMultiple()`, `all()`
- Helper function `has()` to check if a setting exists
- Configurable cache settings with dedicated config file
- Facade support for better Laravel integration
- Comprehensive test suite with 25+ tests
- Singleton pattern for Setting class
- PHP 8.2+ features: `readonly` properties, enhanced type hints, `json_validate()` function

### Changed
- Improved README with detailed usage examples
- Better PHPDoc annotations throughout
- Cleaner architecture with manual config and migration publishing
- Updated to use modern PHP 8.2-8.5 syntax and features

**Full Changelog**: https://github.com/amdad121/setting-laravel/compare/v1.1.0...v2.0.0

## v1.1.0 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/setting-laravel/compare/v1.0.1...v1.1.0

## v1.0.1 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/setting-laravel/compare/v1.0.0...v1.0.1

## v1.0.0 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/setting-laravel/commits/v1.0.0
