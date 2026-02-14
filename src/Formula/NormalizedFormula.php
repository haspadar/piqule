<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula;

use Override;

final readonly class NormalizedFormula implements Formula
{
    public function __construct(
        private string $expression,
    ) {}

    #[Override]
    public function result(): string
    {
        $filtered = preg_replace('/\s*\|\s*/', '|', $this->expression) ?? $this->expression;

        return trim($filtered);
    }
}
