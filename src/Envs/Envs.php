<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Envs;

use Haspadar\Piqule\PiquleException;

/**
 * Environment variables for CI workflows.
 */
interface Envs
{
    /**
     * @throws PiquleException
     * @return array<string, string> variable name => shell command
     */
    public function vars(): array;
}
