<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2025 Konstantinas Mesnikas
 * SPDX-License-Identifier: MIT
 *
 * Local test config that imports the Piqule rules
 */

$rules = require __DIR__ . '/php-cs-fixer/rules.php';
$rules->setFinder(
    PhpCsFixer\Finder::create()
        ->in(__DIR__)
        ->exclude('vendor')
);

return $rules;