<?php

declare(strict_types=1);

namespace Haspadar\Piqule\EnvVar;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;

/**
 * Environment variable required on a developer machine
 */
interface EnvVar
{
    public function name(): string;

    public function url(): string;

    /** @throws PiquleException */
    public function enabled(Config $config): bool;
}
