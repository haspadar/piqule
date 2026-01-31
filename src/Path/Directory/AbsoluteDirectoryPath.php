<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path\Directory;

use Override;

final readonly class AbsoluteDirectoryPath implements DirectoryPath
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
