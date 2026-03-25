<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for SonarQube scanner
 */
final readonly class SonarSection implements ConfigSection
{
    /** @param list<string> $includes */
    public function __construct(private array $includes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'sonar.sources' => $this->includes,
            'sonar.tests' => ['tests'],
            'sonar.exclusions' => [],
            'sonar.php.coverage.reportPaths' => ['.piqule/codecov/coverage.xml'],
            'sonar.enabled' => true,
        ];
    }
}
