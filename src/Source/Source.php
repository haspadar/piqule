<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use Haspadar\Piqule\File\File;

final readonly class Source
{
    public function __construct(
        private File $file,
        private string $id,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function file(): File
    {
        return $this->file;
    }
}
