<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Haspadar\Piqule\PiquleException;

/**
 * A collection of quality checks
 */
interface Checks
{
    /**
     * Returns all checks in this collection.
     *
     * @throws PiquleException
     * @return iterable<Check>
     */
    public function all(): iterable;
}
