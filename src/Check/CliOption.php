<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Haspadar\Piqule\PiquleException;

/**
 * A boolean CLI option that can be enabled or disabled
 */
interface CliOption
{
    /**
     * Whether this option is enabled
     *
     * @throws PiquleException
     */
    public function enabled(): bool;
}
