<?php

declare(strict_types=1);

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\OverrideConfig;

return new OverrideConfig(
    new DefaultConfig(
        exclude: ['vendor', 'tests', '.git', 'templates'],
    ),
    [
        'phpmetrics.coupling.max_efferent' => [25],
        'ci.php.matrix' => ['8.3', '8.4', '8.5'],
        'ci.pr.max_lines_changed' => 2000,
        'ci.piqule_bin' => 'bin/piqule',
        'sonar.organization' => ['haspadar-org'],
        'sonar.projectKey' => ['haspadar_piqule'],
    ],
);
