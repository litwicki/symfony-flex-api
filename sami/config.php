<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in('/var/www/tavro/api/src/Tavro')
    ->in('/var/www/tavro/api/vendor/zoadilack')
;

return new Sami($iterator);