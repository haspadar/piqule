<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholder;

use Override;

final readonly class DefaultPlaceholder implements Placeholder
{
    public function __construct(
        private string $expression,
        private string $default,
    ) {}

    #[Override]
    public function expression(): string
    {
        return $this->expression;
    }

    #[Override]
    public function replacement(): string
    {
        return $this->default;
    }
}
