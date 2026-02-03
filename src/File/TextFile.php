<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Override;

final readonly class TextFile implements File
{
    public function __construct(
        private string $name,
        private string $contents,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function contents(): string
    {
        return $this->contents;
    }
}
