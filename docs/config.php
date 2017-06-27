<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in(realpath(__DIR__ . '/..').'/api/src/Tavro')
    ->in(realpath(__DIR__ . '/..').'/api/vendor/zoadilack')
;

return new Sami($iterator, array(
    'theme'                => 'symfony',
    'title'                => 'Symfony2 API',
    'build_dir'            => __DIR__.'/build',
    'cache_dir'            => __DIR__.'/cache',
    'default_opened_level' => 2,
));