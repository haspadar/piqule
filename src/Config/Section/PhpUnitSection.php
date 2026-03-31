<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for PHPUnit
 */
final readonly class PhpUnitSection implements ConfigSection
{
    /** @param list<string> $includes */
    public function __construct(private array $includes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'phpunit.source.include' => $this->includes,
            'phpunit.testsuites.integration' => ['../../tests/Integration'],
            'phpunit.testsuites.unit' => ['../../tests/Unit'],
            'phpunit.php_options' => '-d memory_limit=1G',
            'phpunit.enabled' => true,
        ];
    }
}
