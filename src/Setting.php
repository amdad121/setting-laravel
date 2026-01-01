<?php

declare(strict_types=1);

namespace AmdadulHaq\Setting;

use AmdadulHaq\Setting\Models\Setting as ModelsSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Setting
{
    protected readonly bool $cacheEnabled;

    protected readonly string $cacheKey;

    protected readonly int $cacheTtl;

    public function __construct()
    {
        $this->cacheEnabled = config('setting.cache_enabled', true);
        $this->cacheKey = config('setting.cache_key', 'settings.cache');
        $this->cacheTtl = config('setting.cache_ttl', 60 * 60 * 24);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->cacheEnabled) {
            $cached = $this->getCachedSettings();

            return $cached->get($key, $default);
        }

        $setting = ModelsSetting::where('key', $key)->first();

        return $setting ? $this->castValue($setting->value) : $default;
    }

    public function set(string $key, mixed $value): ModelsSetting
    {
        $setting = ModelsSetting::updateOrCreate(['key' => $key], ['value' => $this->serializeValue($value)]);

        if ($this->cacheEnabled) {
            $this->flushCache();
        }

        return $setting;
    }

    public function remove(string $key): bool
    {
        $deleted = (bool) ModelsSetting::where('key', $key)->delete();

        if ($deleted && $this->cacheEnabled) {
            $this->flushCache();
        }

        return $deleted;
    }

    public function has(string $key): bool
    {
        if ($this->cacheEnabled) {
            return $this->getCachedSettings()->has($key);
        }

        return ModelsSetting::where('key', $key)->exists();
    }

    public function all(): Collection
    {
        if ($this->cacheEnabled) {
            return $this->getCachedSettings();
        }

        return $this->loadSettings();
    }

    public function setMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            ModelsSetting::updateOrCreate(['key' => $key], ['value' => $this->serializeValue($value)]);
        }

        if ($this->cacheEnabled) {
            $this->flushCache();
        }
    }

    public function getMultiple(array $keys, mixed $default = null): array
    {
        return collect($keys)
            ->mapWithKeys(fn (string $key): array => [$key => $this->get($key, $default)])
            ->all();
    }

    public function flushCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    protected function getCachedSettings(): Collection
    {
        return Cache::remember($this->cacheKey, $this->cacheTtl, fn (): Collection => $this->loadSettings());
    }

    protected function loadSettings(): Collection
    {
        return ModelsSetting::all()
            ->mapWithKeys(fn (ModelsSetting $setting): array => [$setting->key => $this->castValue($setting->value)]);
    }

    protected function serializeValue(mixed $value): string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return 'null';
        }

        return (string) $value;
    }

    protected function castValue(string $value): mixed
    {
        if (json_validate($value)) {
            return json_decode($value, true);
        }

        $lowerValue = strtolower($value);
        if ($lowerValue === 'true') {
            return true;
        }

        if ($lowerValue === 'false') {
            return false;
        }

        if ($lowerValue === 'null') {
            return null;
        }

        if (is_numeric($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }

        return $value;
    }
}
