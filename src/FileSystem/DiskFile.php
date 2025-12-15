<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

use Haspadar\Piqule\PiquleException;

final readonly class DiskFile implements File
{
    public function __construct(
        private string $path,
    ) {}

    public function path(): string
    {
        return $this->path;
    }

    public function exists(): bool
    {
        return is_file($this->path);
    }

    public function hash(): string
    {
        if (!$this->exists()) {
            throw new PiquleException(
                sprintf('File does not exist: "%s"', $this->path),
            );
        }

        $hash = hash_file('sha256', $this->path);

        if ($hash === false) {
            throw new PiquleException(
                sprintf('Failed to hash file: "%s"', $this->path),
            );
        }

        return $hash;
    }

    public function contents(): string
    {
        $contents = file_get_contents($this->path);
        if ($contents === false) {
            throw new PiquleException(
                sprintf('Failed to read file: "%s"', $this->path),
            );
        }

        return $contents;
    }
}
