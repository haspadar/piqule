<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

final readonly class DirectoryPath
{
    public function __construct(
        private string $value,
    ) {}

    public function value(): string
    {
        return $this->value;
    }
}
