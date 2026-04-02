<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for CI workflows
 */
final readonly class CiSection implements ConfigSection
{
    /** @param list<string> $phpVersions */
    public function __construct(private array $phpVersions) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'php.versions' => $this->phpVersions,
            'ci.piqule_bin' => 'vendor/bin/piqule',
            'ci.pr.max_lines_changed' => 250,
        ];
    }
}
