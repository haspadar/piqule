<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

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
}
