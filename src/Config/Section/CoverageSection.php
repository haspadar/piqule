<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for code coverage thresholds
 */
final readonly class CoverageSection implements ConfigSection
{
    #[Override]
    public function toArray(): array
    {
        return [
            'coverage.patch.target' => 80,
            'coverage.patch.threshold' => 5,
            'coverage.project.target' => 80,
            'coverage.project.threshold' => 2,
        ];
    }
}
