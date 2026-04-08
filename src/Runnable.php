<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

use UnexpectedValueException;

/** A unit of work that can be executed. */
interface Runnable
{
    /**
     * @throws PiquleException
     * @throws UnexpectedValueException
     */
    public function run(): void;
}
