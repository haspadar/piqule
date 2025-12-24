<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

final readonly class RunContext
{
    /**
     * Raw CLI arguments as received from the entry point
     *
     * @var array<int, string>
     */
    private array $argv;

    /**
     * @param array<int, string> $argv Raw CLI arguments (usually $argv)
     */
    public function __construct(array $argv)
    {
        $this->argv = $argv;
    }

    /**
     * Returns the working directory of the invocation
     *
     * The directory is resolved in the following order:
     * - COMPOSER_CWD environment variable (when executed via Composer)
     * - current working directory
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

    /**
     * Returns the requested command name
     *
     * This is a convenience shortcut for argument(1).
     *
     * @throws PiquleException If the command argument is missing
     */
    public function command(): string
    {
        return $this->argument(1);
    }

    /**
     * Returns the CLI argument at the given index
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
