<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Override;

final readonly class ConfigScalarValue implements ConfigValue
{
    public function __construct(
        private string|int|float|bool $value,
    ) {}

    #[Override]
    public function value(): string|int|float|bool
    {
        return $this->value;
    }
}
