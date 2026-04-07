<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Secret;

/**
 * Collection of CI secrets
 */
final readonly class Secrets
{
    /** @param list<Secret> $items */
    public function __construct(private array $items) {}

    /** @return list<Secret> */
    public function items(): array
    {
        return $this->items;
    }
}
