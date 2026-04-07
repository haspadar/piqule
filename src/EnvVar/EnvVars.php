<?php

declare(strict_types=1);

namespace Haspadar\Piqule\EnvVar;

/**
 * Collection of local environment variables
 */
final readonly class EnvVars
{
    /** @param list<EnvVar> $items */
    public function __construct(private array $items) {}

    /** @return list<EnvVar> */
    public function items(): array
    {
        return $this->items;
    }
}
