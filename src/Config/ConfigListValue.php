<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Override;

final readonly class ConfigListValue implements ConfigValue
{
    /**
     * @param list<bool|int|float|string> $values
     */
    public function __construct(private array $values) {}

    /**
     * @return list<bool|int|float|string>
     */
    #[Override]
    public function value(): array
    {
        return $this->values;
    }
}
