<?php

declare(strict_types=1);

return [
    'thresholds' => [
        // Cyclomatic complexity
        'ccnMethodMax'     => 10,

        // Size
        'nbMethods'        => 10,
        'loc'              => 100, // ~ 50 LOC per method × 4

        // Coupling
        'efferentCoupling' => 5,
    ],
];
