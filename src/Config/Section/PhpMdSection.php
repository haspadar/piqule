<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for PHPMD
 */
final readonly class PhpMdSection implements ConfigSection
{
    /** @param list<string> $includes */
    public function __construct(private array $includes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'phpmd.class_complexity' => [50],
            'phpmd.class_length' => [200],
            'phpmd.cyclomatic' => [10],
            'phpmd.max_fields' => [10],
            'phpmd.max_methods' => [10],
            'phpmd.max_parameters' => [5],
            'phpmd.method_length' => [50],
            'phpmd.npath' => [200],
            'phpmd.paths' => $this->includes,
            'phpmd.enabled' => true,
        ];
    }
}
