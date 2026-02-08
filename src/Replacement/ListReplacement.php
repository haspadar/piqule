<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Replacement;

use Override;

final readonly class ListReplacement implements Replacement
{
    /**
     * @param array<string> $values
     */
    public function __construct(
        private array $values,
        private string $format,
        private string $join,
    ) {}

    #[Override]
    public function value(): string
    {
        return implode(
            $this->join,
            array_map(
                fn(string $value): string => sprintf($this->format, $value),
                $this->values,
            ),
        );
    }

    public function withDefault(Replacement $default): Replacement
    {
        return $this;
    }
}
