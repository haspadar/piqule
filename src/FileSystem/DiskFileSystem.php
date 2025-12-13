<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

use Haspadar\Piqule\PiquleException;

final readonly class DiskFileSystem implements FileSystem
{
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    public function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    public function createDirectory(string $path): void
    {
        mkdir($path, 0o777, true);
    }

    public function ensureDirectory(string $target): void
    {
        if (!$this->isDirectory($target)) {
            $this->createDirectory($target);
        }
    }

    public function copy(string $source, string $target): void
    {
        if (!copy($source, $target)) {
            throw new PiquleException(
                sprintf('Failed to copy "%s" to "%s"', $source, $target)
            );
        }
    }
}
