<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Override;

/**
 * A single-element collection for a specifically requested check.
 */
final readonly class SingleCheck implements Checks
{
    /** Initializes with the check name and project root path. */
    public function __construct(private string $name, private string $root) {}

    #[Override]
    public function all(): iterable
    {
        yield new ConfigCheck($this->name, $this->root);
    }
}
