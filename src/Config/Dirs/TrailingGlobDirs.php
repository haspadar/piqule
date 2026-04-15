<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Dirs;

use Override;

/**
 * Directories suffixed with /** for recursive glob exclusion patterns.
 */
final readonly class TrailingGlobDirs implements Dirs
{
    /**
     * Initializes with directory paths to transform.
     *
     * @param list<string> $dirs
     */
    public function __construct(private array $dirs) {}

    #[Override]
    public function toList(): array
    {
        return array_map(static fn(string $dir): string => "{$dir}/**", $this->dirs);
    }
}
