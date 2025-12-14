<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

interface FileSystem
{
    public function exists(string $path): bool;

    public function isDirectory(string $path): bool;

    public function createDirectory(string $path): void;

    public function ensureDirectory(string $target): void;

    public function copy(string $source, string $target): void;
}
