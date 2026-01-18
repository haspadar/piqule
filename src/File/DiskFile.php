<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class DiskFile implements File
{
    public function __construct(
        private string $path,
    ) {}

    #[Override]
    public function id(): string
    {
        $realPath = realpath($this->path);

        if ($realPath === false) {
            throw new PiquleException(
                sprintf('Failed to resolve file path: "%s"', $this->path),
            );
        }

        return $realPath;
    }

    #[Override]
    public function contents(): string
    {
        if (!is_file($this->path)) {
            throw new PiquleException(
                sprintf('Failed to read file: "%s"', $this->path),
            );
        }

        $contents = file_get_contents($this->path);
        if ($contents === false) {
            throw new PiquleException(sprintf('File is not readable: "%s"', $this->path));
        }

        return $contents;
    }
}
