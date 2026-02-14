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
        $filtered = $this->collapseWhitespace($this->expression);
        $filtered = $this->normalizePipes($filtered);

        return trim($filtered);
    }

    private function collapseWhitespace(string $input): string
    {
        return preg_replace('/\s+/', ' ', $input) ?? $input;
    }

    private function normalizePipes(string $input): string
    {
        return preg_replace('/\s*\|\s*/', '|', $input) ?? $input;
    }
}
