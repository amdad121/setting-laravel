<?php

declare(strict_types=1);

namespace AmdadulHaq\Setting;

use AmdadulHaq\Setting\Models\Setting as ModelsSetting;

class Setting
{
    public function get($key, $default = null): ?string
    {
        return ModelsSetting::get($key, $default);
    }

    public function set($key, $value): ModelsSetting
    {
        return ModelsSetting::set($key, $value);
    }

    public function remove($key): bool
    {
        return ModelsSetting::remove($key);
    }
}
