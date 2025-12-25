<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

final readonly class CommandLine
{
    public function __construct(private array $argv) {}

    /**
     * Returns the normalized command string passed to the CLI.
     *
     * The command is built from argv arguments excluding the binary name.
     * Each argument is trimmed, empty arguments are removed, and the result
     * is joined with single spaces to ensure stable matching regardless of
     * input whitespace.
     *
     * Examples:
     *  - ['piqule', 'sync', '--dry-run']        => 'sync --dry-run'
     *  - ['piqule', 'sync', '   --dry-run']    => 'sync --dry-run'
     */
    public function command(): string
    {
        return implode(
            ' ',
            array_values(
                array_filter(
                    array_map(
                        static fn(string $arg): string => trim($arg),
                        array_slice($this->argv, 1),
                    ),
                    static fn(string $arg): bool => $arg !== '',
                ),
            ),
        );
    }
}
