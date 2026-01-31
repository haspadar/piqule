<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path\Directory;

use Haspadar\Piqule\Path\Path;
use Override;

final readonly class AbsoluteDirectoryPath implements Path
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
