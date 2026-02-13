<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use InvalidArgumentException;
use Override;

final readonly class ParsedArgs implements Args
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
        $values = $this->origin->values();

        if ($values === []) {
            throw new InvalidArgumentException(
                'Expected php list literal, got empty input',
            );
        }

        $raw = $values[0];

        if (!is_string($raw)) {
            throw new InvalidArgumentException(
                sprintf('Expected php list literal string, got %s', get_debug_type($raw)),
            );
        }

        if ($raw === '' || $raw[0] !== '[' || $raw[strlen($raw) - 1] !== ']') {
            throw new InvalidArgumentException(
                sprintf('Expected php list literal, got "%s"', $raw),
            );
        }

        $inner = trim(substr($raw, 1, -1));

        if ($inner === '') {
            return [];
        }

        return explode(',', $inner);
    }
}
