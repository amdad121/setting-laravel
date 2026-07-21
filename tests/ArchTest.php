<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();
