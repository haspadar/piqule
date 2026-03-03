<?php

declare(strict_types=1);

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\OverrideConfig;

return new OverrideConfig(new DefaultConfig(), [
    'markdownlint.ignores' => [
        '**/vendor/**',
        '**/node_modules/**',
        '**/coverage/**',
        '**/.git/**',
        '**/templates/**',
    ],

    'yamllint.ignore' => [
        'vendor/**',
        'node_modules/**',
        'build/**',
        'var/**',
        'templates/**',
        '.piqule/**/html/**',
        '.piqule/**/coverage-report/**',
    ],
    'yamllint.line_length.max' => [120],

    'phpcs.excludes' => ['vendor/*', 'tests/*', 'templates/*'],

    'shellcheck.ignore_dirs' => ['vendor', '.git', 'templates'],

    'ci.php.matrix' => ['8.3', '8.4', '8.5'],
    'ci.pr.max_lines_changed' => 400,
    'ci.piqule_bin' => 'bin/piqule',
]);
