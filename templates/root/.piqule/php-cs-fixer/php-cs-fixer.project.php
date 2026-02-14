<?php

declare(strict_types=1);

use PhpCsFixer\Finder;

/** @var PhpCsFixer\Config $rules */
$rules = require __DIR__ . '/php-cs-fixer.php';

$rules->setFinder(
    Finder::create()
        ->in([<< config(php_cs_fixer.paths) |default(["../.."]) |format("__DIR__ . '/%s'") |join(",\n") >>])
        ->exclude([<< config(php_cs_fixer.exclude)|default(["vendor"])|format("'%s'")|join(",") >>]),
)->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');

return $rules;
