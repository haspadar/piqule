<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Replacement;

use Override;

final readonly class ScalarReplacement implements Replacement
{
    public function __construct(
        private string $value,
    ) {}

    #[Override]
    public function value(): string
    {
        return $this->value;
    }

    #[Override]
    public function withDefault(Replacement $default): Replacement
    {
        return $this;
    }
}
