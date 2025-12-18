<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

/**
 * Represents the execution context of the Piqule process.
 */
final class RunContext
{
    /** @var array<int, string> */
    private readonly array $argv;

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

    public function command(): string
    {
        return $this->argument(1);
    }

    /**
     * Returns the CLI argument at the given index.
     *
     * @throws PiquleException If the argument is missing
     */
    public function argument(int $index): string
    {
        if (!array_key_exists($index, $this->argv)) {
            throw new PiquleException(sprintf('Missing argument #%d', $index));
        }

        return $this->argv[$index];
    }
}
