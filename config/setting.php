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

];
