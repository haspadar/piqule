<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Token;

/**
 * Collection of external service tokens
 */
final readonly class Tokens
{
    /** @param list<Token> $items */
    public function __construct(private array $items) {}

    /** @return list<Token> */
    public function items(): array
    {
        return $this->items;
    }
}
