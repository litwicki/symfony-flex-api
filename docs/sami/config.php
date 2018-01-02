<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in(__DIR__ . '/api')
    ->in(__DIR__ . '/api')
;

return new Sami($iterator, array(
    'title'                => 'Tavro API',
    'build_dir'            => __DIR__.'/build',
    'cache_dir'            => __DIR__.'/cache',
    'default_opened_level' => 2,
));