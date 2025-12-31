<?php

declare(strict_types=1);

return [
    'thresholds' => [
        // Cyclomatic complexity
        'ccnMethodMax'     => 10,

        // Size
        'nbMethods'        => 10,
        'loc'              => 100, // Max lines per class

        // Coupling
        'efferentCoupling' => 5,
    ],

    'metrics' => [
        // min required
        'maintainabilityIndex' => [
            'min' => 70,
        ],
    ],
];
