<?php

declare(strict_types=1);

namespace AmdadulHaq\Setting\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static \AmdadulHaq\Setting\Models\Setting set(string $key, mixed $value)
 * @method static bool remove(string $key)
 * @method static bool has(string $key)
 * @method static \Illuminate\Support\Collection all()
 * @method static void setMultiple(array $settings)
 * @method static array getMultiple(array $keys, mixed $default = null)
 * @method static void flushCache()
 *
 * @see \AmdadulHaq\Setting\Setting
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AmdadulHaq\Setting\Setting::class;
    }
}
