<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use InvalidArgumentException;
use Override;
use Throwable;

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

        try {
            /** @var mixed $result */
            $result = eval('return ' . $raw . ';');
        } catch (Throwable) {
            throw new InvalidArgumentException(
                sprintf('Invalid PHP list literal "%s"', $raw),
            );
        }

        if (!is_array($result) || !array_is_list($result)) {
            throw new InvalidArgumentException(
                sprintf('Expected PHP list literal, got "%s"', $raw),
            );
        }

        foreach ($result as $item) {
            if (!is_int($item) && !is_float($item) && !is_string($item) && !is_bool($item)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'PHP list literal must contain only scalars, got %s',
                        get_debug_type($item),
                    ),
                );
            }
        }

        /** @var list<int|float|string|bool> $result */
        return $result;
    }
}
