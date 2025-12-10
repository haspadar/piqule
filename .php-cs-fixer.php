<?php

declare(strict_types=1);

/** @var PhpCsFixer\Config $rules */
$rules = require __DIR__ . '/php-cs-fixer/rules.php';
$rules->setFinder(
    PhpCsFixer\Finder::create()
        ->in(__DIR__)
        ->exclude('vendor')
);

return $rules;