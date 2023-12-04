<?php

declare(strict_types=1);

namespace AmdadulHaq\Setting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $key
 * @property string $value
 *
 * @method static where(string $string, $key)
 * @method static updateOrCreate(array $array, array $array1)
 */
class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key, $default = null): ?string
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value): self
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function remove($key): bool
    {
        return (bool) self::where('key', $key)->delete();
    }
}
