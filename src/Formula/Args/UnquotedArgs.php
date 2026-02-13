<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use Override;

/**
 * Removes matching outer single or double quotes
 *
 * Escape sequences inside the string are not processed
 */
final readonly class UnquotedArgs implements Args
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
            fn(int|float|string|bool $value) =>
            is_string($value)
                ? $this->unquote($value)
                : $value,
            $this->origin->values(),
        );
    }

    private function unquote(string $text): string
    {
        $length = strlen($text);

        if ($length < 2) {
            return $text;
        }

        $first = $text[0];
        $last = $text[$length - 1];

        if (
            ($first === '"' && $last === '"')
            || ($first === "'" && $last === "'")
        ) {
            return substr($text, 1, -1);
        }

        return $text;
    }
}
