<?php

declare(strict_types=1);

use AmdadulHaq\Setting\Setting;

if (! function_exists('setting')) {
    function setting(): Setting
    {
        return resolve(Setting::class);
    }
}
