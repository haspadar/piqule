<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

/**
 * Represents the execution context of the Piqule process.
 */
final readonly class CommandLine
{
    public function __construct(private array $argv) {}

    public function command(): string
    {
        return implode(
            ' ',
            array_slice($this->argv, 1),
        );
    }
}
