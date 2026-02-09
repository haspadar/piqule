<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class ConfigMissingValue implements ConfigValue
{
    public function __construct(private string $name) {}

    #[Override]
    public function value(): never
    {
        throw new PiquleException(
            sprintf('Config value "%s" is missing', $this->name),
        );
    }
}
