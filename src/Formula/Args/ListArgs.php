<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use Override;

/**
 * Args backed by a plain PHP list of scalar values
 */
final readonly class ListArgs implements Args
{
    /** @param list<int|float|string|bool> $values */
    public function __construct(
        private array $values,
    ) {}

    /** @return list<int|float|string|bool> */
    #[Override]
    public function values(): array
    {
        return $this->values;
    }
}
