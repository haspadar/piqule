<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for CI workflows
 */
final readonly class CiSection implements ConfigSection
{
    /**
     * @param list<string> $phpMatrix
     * @param list<string> $phpTestVersion
     */
    public function __construct(
        private array $phpMatrix,
        private array $phpTestVersion,
    ) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'ci.php.matrix' => $this->phpMatrix,
            'ci.php.test_version' => $this->phpTestVersion,
            'ci.piqule_bin' => 'vendor/bin/piqule',
            'ci.pr.max_lines_changed' => 250,
        ];
    }
}
