<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

interface FileSystem
{
    public function exists(string $path): bool;

    public function isDirectory(string $path): bool;

    public function createDirectory(string $path): void;
}
