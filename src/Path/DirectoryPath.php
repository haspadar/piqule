<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path;

use Override;

final readonly class DirectoryPath implements Path
{
    public function __construct(
        private string $value,
    ) {}

    #[Override]
    public function value(): string
    {
        return $this->value;
    }
}
