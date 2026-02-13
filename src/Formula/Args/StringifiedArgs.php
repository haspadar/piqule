<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use Override;

final readonly class StringifiedArgs implements Args
{
    public function __construct(
        private Args $origin,
    ) {}

    /**
     * @return list<string>
     */
    #[Override]
    public function values(): array
    {
        return array_map(
            fn(int|float|string|bool $value): string =>
            $this->stringify($value),
            $this->origin->values(),
        );
    }

    private function stringify(int|float|string|bool $value): string
    {
        return match ($value) {
            true => 'true',
            false => 'false',
            default => (string) $value,
        };
    }
}
