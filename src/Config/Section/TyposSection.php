<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Haspadar\Piqule\Config\Dirs\TrailingSlashDirs;
use Override;

/**
 * Default configuration for typos
 */
final readonly class TyposSection implements ConfigSection
{
    /** @param list<string> $excludes */
    public function __construct(private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'typos.exclude' => (new TrailingSlashDirs($this->excludes))->toList(),
            'typos.enabled' => true,
        ];
    }
}
