<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use Override;

final readonly class ArrayArgs implements Args
{
    public function __construct(
        private array $items,
    ) {}

    #[Override]
    public function text(): string
    {
        return sprintf(
            '[%s]',
            implode(',', $this->items),
        );
    }

    #[Override]
    public function list(): array
    {
        return $this->items;
    }
}
