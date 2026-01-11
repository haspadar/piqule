<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

final readonly class Cli
{
    /**
     * @param array<int, string> $argv
     */
    public function __construct(
        private array $argv,
    ) {}

    public function command(): string
    {
        if (!isset($this->argv[1])) {
            throw new PiquleException('Command is required');
        }

        return $this->argv[1];
    }

    public function isDryRun(): bool
    {
        return in_array('--dry-run', $this->argv, true);
    }
}
