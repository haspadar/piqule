<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Override;

/**
 * A boolean flag parsed from CLI arguments.
 */
final readonly class ToggleOption implements CliOption
{
    /**
     * Initializes with CLI arguments and flag variants.
     *
     * @param list<string> $argv
     * @param list<string> $flags
     */
    public function __construct(private array $argv, private array $flags) {}

    #[Override]
    public function enabled(): bool
    {
        foreach ($this->flags as $flag) {
            if (in_array($flag, $this->argv, true)) {
                return true;
            }
        }

        return false;
    }
}
