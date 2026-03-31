<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Haspadar\Piqule\Config\Dirs\ProjectDirs;
use Override;

/**
 * Default configuration for Psalm
 */
final readonly class PsalmSection implements ConfigSection
{
    /**
     * @param list<string> $includes
     * @param list<string> $excludes
     */
    public function __construct(private array $includes, private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'psalm.error_level' => [1],
            'psalm.project.directories' => $this->includes,
            'psalm.project.ignore' => (new ProjectDirs($this->excludes))->toList(),
            'psalm.enabled' => true,
        ];
    }
}
