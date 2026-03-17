<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Dirs;

use Override;

/**
 * Directories suffixed with /* for glob-style exclusion patterns
 */
final readonly class GlobDirs implements Dirs
{
    /** @param list<string> $dirs */
    public function __construct(private array $dirs) {}

    /** @return list<string> */
    #[Override]
    public function toList(): array
    {
        return array_map(fn(string $dir): string => $dir . '/*', $this->dirs);
    }
}
