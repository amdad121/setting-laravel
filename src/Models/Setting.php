<?php

declare(strict_types=1);

namespace AmdadulHaq\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $key
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, ?string $value): self
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function remove(string $key): bool
    {
        return (bool) self::where('key', $key)->delete();
    }
}
