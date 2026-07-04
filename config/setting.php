<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configure caching behavior for the settings package.
    |
    */

    'cache_enabled' => env('SETTING_CACHE_ENABLED', true),

    'cache_key' => env('SETTING_CACHE_KEY', 'settings.cache'),

    'cache_ttl' => env('SETTING_CACHE_TTL', 60 * 60 * 24), // 24 hours

    /*
    |--------------------------------------------------------------------------
    | Overwrite App Config
    |--------------------------------------------------------------------------
    |
    | When enabled, any stored setting whose key matches a config path
    | (e.g. "mail.from.address") will overwrite that config value on boot,
    | letting you change config at runtime without redeploying.
    |
    */

    'overwrite_config' => env('SETTING_OVERWRITE_CONFIG', false),

    /*
    |--------------------------------------------------------------------------
    | Overwrite Config Keys
    |--------------------------------------------------------------------------
    |
    | Restrict automatic config overwriting to this list of setting keys.
    | Leave empty to apply every stored setting whose key matches a config
    | path.
    |
    */

    'overwrite_config_keys' => [],

];
