<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for PHPStan
 */
final readonly class PhpStanSection implements ConfigSection
{
    /** @param list<string> $includes */
    public function __construct(private array $includes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'phpstan.level' => [9],
            'phpstan.memory' => '1G',
            'phpstan.paths' => $this->includes,
            'phpstan.checked_exceptions' => ['\Throwable'],
            'phpstan.neon_includes' => ['../../vendor/phpstan/phpstan-strict-rules/rules.neon'],
            'phpstan.enabled' => true,
        ];
    }
}
