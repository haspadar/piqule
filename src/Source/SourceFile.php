<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

final readonly class SourceFile
{
    public function __construct(
        private File $file,
        private string $relativePath,
    ) {}

    public function relativePath(): string
    {
        return $this->relativePath;
    }

    public function file(): File
    {
        return $this->file;
    }
}
