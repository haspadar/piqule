<?php

return [
    'markdownlint' => [
        'ignores' => [
            '**/vendor/**',
            '**/node_modules/**',
            '**/coverage/**',
            '**/.git/**',
            '**/templates/**',
        ],
    ],

    'yamllint' => [
        'ignore' => [
            'vendor/**',
            'node_modules/**',
            'build/**',
            'var/**',
            'templates/**',
            '.piqule/**/html/**',
            '.piqule/**/coverage-report/**',
        ],
        'line_length' => [
            'max' => 120,
        ],
    ],

    'ci' => [
        'php' => [
            'matrix' => ['8.3', '8.5'],
        ],

        'phpmd' => [
            'php_version' => '8.3',
        ],

        'tests' => [
            'php_version' => '8.3',
        ],

        'pr' => [
            'max_lines_changed' => 400,
        ],
    ],
];
