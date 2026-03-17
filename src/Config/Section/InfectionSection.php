<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for Infection
 */
final readonly class InfectionSection implements ConfigSection
{
    /** @param list<string> $includes */
    public function __construct(private array $includes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'infection.php_options' => '-d memory_limit=1G',
            'infection.source.directories' => $this->includes,
            'infection.timeout' => '30',
            'infection.enabled' => true,
        ];
    }
}
