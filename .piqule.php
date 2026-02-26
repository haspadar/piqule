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
    'phpcs' => [
        'excludes' => [
            'vendor/*',
            'tests/*',
            'templates/*',
        ]
    ],
    'shellcheck' => [
        'ignore_dirs' => [
            'vendor',
            '.git',
            'templates'
        ]
    ],

    'ci' => [
        'php' => [
            'matrix' => ['8.3', '8.4', '8.5'],
        ],
        'pr' => [
            'max_lines_changed' => 400,
        ],
    ],

    'docker' => [
        'image' => 'piqule-infra:local',
    ],
];
