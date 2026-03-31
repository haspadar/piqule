<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Dirs;

use Override;

/**
 * Directories prefixed with ../../ for tools that run inside .piqule/<tool>/
 */
final readonly class ProjectDirs implements Dirs
{
    /** @param list<string> $dirs */
    public function __construct(private array $dirs) {}

    /** @return list<string> */
    #[Override]
    public function toList(): array
    {
        return array_map(static fn(string $dir): string => '../../' . $dir, $this->dirs);
    }
}
