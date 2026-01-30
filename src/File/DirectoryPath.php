<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Path\Path;

final readonly class DirectoryPath implements Path
{
    public function __construct(
        private string $value,
    ) {}

    public function value(): string
    {
        return $this->value;
    }
}
