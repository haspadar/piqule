<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Override;

/**
 * Directories as negated glob patterns for exclusion (e.g. !vendor/**)
 */
final readonly class NegatedGlobDirs implements Dirs
{
    /** @param list<string> $dirs */
    public function __construct(private array $dirs) {}

    /** @return list<string> */
    #[Override]
    public function toList(): array
    {
        return array_map(fn(string $dir): string => '!' . $dir . '/**', $this->dirs);
    }
}
