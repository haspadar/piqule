<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use Override;

final readonly class TrimmedArgs implements Args
{
    public function __construct(
        private Args $origin,
    ) {}

    #[Override]
    public function text(): string
    {
        return trim($this->origin->text());
    }

    #[Override]
    public function list(): array
    {
        return array_map(
            static fn(string $value) => trim($value),
            $this->origin->list(),
        );
    }
}
