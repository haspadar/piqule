<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Override;

/**
 * Directories suffixed with / to match directory entries explicitly
 */
final readonly class TrailingSlashDirs implements Dirs
{
    /** @param list<string> $dirs */
    public function __construct(private array $dirs) {}

    /** @return list<string> */
    #[Override]
    public function toList(): array
    {
        return array_map(fn(string $dir): string => $dir . '/', $this->dirs);
    }
}
