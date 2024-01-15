<?php

declare(strict_types=1);

use AmdadulHaq\Setting\Models\Setting;

if (! function_exists('setting')) {
    function setting(): Setting
    {
        return new Setting();
    }
}
