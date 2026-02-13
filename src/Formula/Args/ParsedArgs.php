<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use InvalidArgumentException;
use JsonException;
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
        $raw = $this->singleRawValue();
        $decoded = $this->decodeJsonList($raw);
        $this->assertScalarList($decoded, $raw);

        /** @var list<int|float|string|bool> $decoded */
        return $decoded;
    }

    private function singleRawValue(): string
    {
        $values = $this->origin->values();

        if ($values === []) {
            throw new InvalidArgumentException(
                'Expected JSON list literal, got empty input',
            );
        }

        if (count($values) !== 1) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected single JSON list literal, got %d values',
                    count($values),
                ),
            );
        }

        $raw = $values[0];

        if (!is_string($raw)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected JSON list literal string, got %s',
                    get_debug_type($raw),
                ),
            );
        }

        return $raw;
    }

    /**
     * @return array<array-key, mixed>
     */
    private function decodeJsonList(string $raw): array
    {
        try {
            $decoded = json_decode(
                $raw,
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (JsonException) {
            throw new InvalidArgumentException(
                sprintf('Invalid JSON list literal "%s"', $raw),
            );
        }

        if (!is_array($decoded)) {
            throw new InvalidArgumentException(
                sprintf('Expected JSON list literal, got "%s"', $raw),
            );
        }

        return $decoded;
    }

    /**
     * @param array<array-key, mixed> $decoded
     */
    private function assertScalarList(array $decoded, string $raw): void
    {
        if (!is_array($decoded) || !array_is_list($decoded)) {
            throw new InvalidArgumentException(
                sprintf('Expected JSON list literal, got "%s"', $raw),
            );
        }

        foreach ($decoded as $item) {
            if (!is_int($item)
                && !is_float($item)
                && !is_string($item)
                && !is_bool($item)
            ) {
                throw new InvalidArgumentException(
                    sprintf(
                        'JSON list literal must contain only scalars, got %s',
                        get_debug_type($item),
                    ),
                );
            }
        }
    }
}
