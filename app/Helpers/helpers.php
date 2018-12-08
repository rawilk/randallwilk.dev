<?php

use Symfony\Component\Finder\Finder;

// Require all helper files in the `Functions` directory
$files = Finder::create()
    ->files()
    ->in(__DIR__ . '/Functions')
    ->depth(0)
    ->name('*.php');

foreach ($files as $file) {
    require_once $file;
}
