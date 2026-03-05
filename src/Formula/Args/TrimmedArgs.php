<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use Override;

/**
 * Trims leading and trailing whitespace from string values; non-strings are passed through unchanged
 */
final readonly class TrimmedArgs implements Args
{
    public function __construct(
        private Args $origin,
    ) {}

    /**
     * @return list<int|float|string|bool>
     */
    #[Override]
    public function values(): array
    {
        return array_map(
            static function (int|float|string|bool $value): int|float|string|bool {
                if (!is_string($value)) {
                    return $value;
                }

                return trim($value);
            },
            $this->origin->values(),
        );
    }
}
