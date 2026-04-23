<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Dirs;

use Override;

/**
 * Directories prefixed with ../../ for tools that run inside .piqule/<tool>/.
 */
final readonly class ProjectDirs implements Dirs
{
    /**
     * Initializes with directory paths to prefix.
     *
     * @param list<string> $dirs Directory paths to prefix with ../../ for tool subdirectory
     */
    public function __construct(private array $dirs) {}

    #[Override]
    public function toList(): array
    {
        return array_map(static fn(string $dir): string => "../../{$dir}", $this->dirs);
    }
}
