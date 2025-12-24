<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

/**
 * Represents the execution context of the Piqule process.
 */
final readonly class RunContext
{
    /** @var array<int, string> */
    private array $argv;

    /**
     * @param array<int, string> $argv
     */
    public function __construct(array $argv)
    {
        $this->argv = $argv;
    }

    /**
     * Returns the working directory of the invocation.
     *
     * @throws PiquleException If the working directory cannot be determined
     */
    public function root(): string
    {
        $root = getenv('COMPOSER_CWD') ?: getcwd();

        if ($root === false) {
            throw new PiquleException('Cannot determine working directory');
        }

        return $root;
    }

    public function commandLine(): string
    {
        return implode(
            ' ',
            array_slice($this->argv, 1),
        );
    }

    public function isDryRun(): bool
    {
        return in_array('--dry-run', $this->argv, true);
    }
}
