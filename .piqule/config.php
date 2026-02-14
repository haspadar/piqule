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
        'pr' => [
            'max_lines_changed' => 400,
        ],
    ],
];
