<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for SonarQube scanner
 */
final readonly class SonarSection implements ConfigSection
{
    /**
     * @param list<string> $includes
     * @param list<string> $excludes
     */
    public function __construct(
        private array $includes,
        private array $excludes,
    ) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'sonar.sources'             => $this->includes,
            'sonar.tests'               => ['../../tests'],
            'sonar.cpd.exclusions'      => $this->excludes,
            'sonar.coverage.exclusions' => $this->excludes,
            'sonar.enabled'             => true,
        ];
    }
}
