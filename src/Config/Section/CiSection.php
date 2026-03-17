<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for CI workflows
 */
final readonly class CiSection implements ConfigSection
{
    /** @param list<string> $phpVersion */
    public function __construct(private array $phpVersion) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'ci.php.matrix' => $this->phpVersion,
            'ci.php.test_version' => $this->phpVersion,
            'ci.piqule_bin' => 'vendor/bin/piqule',
            'ci.pr.max_lines_changed' => 250,
        ];
    }
}
