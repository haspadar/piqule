<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Override;

/**
 * A CLI option that falls back to a default when neither flag is set.
 */
final readonly class ConfigOption implements CliOption
{
    /** Initializes with positive flag, negative flag, and default. */
    public function __construct(
        private CliOption $yes,
        private CliOption $off,
        private CliOption $default,
    ) {}

    #[Override]
    public function enabled(): bool
    {
        if ($this->off->enabled()) {
            return false;
        }

        return $this->yes->enabled() || $this->default->enabled();
    }
}
