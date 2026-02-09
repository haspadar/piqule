<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;

interface ConfigValue
{
    /**
     * @throws PiquleException if value is missing
     *
     * @return bool|int|float|string|list<bool|int|float|string>
     */
    public function value(): bool|int|float|string|array;
}
