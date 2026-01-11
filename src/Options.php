<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

final readonly class Options
{
    /**
     * @param array<int, string> $argv
     */
    public function __construct(
        private array $argv,
    ) {
    }

    public function isDryRun(): bool
    {
        return in_array('--dry-run', $this->argv, true);
    }
}
