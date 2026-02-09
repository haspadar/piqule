<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class ConfigMissingValue implements ConfigValue
{
    #[Override]
    public function value(): never
    {
        throw new PiquleException('Config value is missing');
    }
}
