<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class DiskStorage implements Storage
{
    public function __construct(
        private string $root,
    ) {}

    #[Override]
    public function exists(string $name): bool
    {
        return is_file($this->path($name));
    }

    #[Override]
    public function read(string $name): string
    {
        $path = $this->path($name);

        if (!is_file($path) || !is_readable($path)) {
            throw new PiquleException(
                sprintf('Failed to read file "%s"', $name),
            );
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new PiquleException(
                sprintf('Failed to read file "%s"', $name),
            );
        }

        return $contents;
    }

    #[Override]
    public function write(string $name, string $contents): void
    {
        $path = $this->path($name);
        $dir = dirname($path);

        if (!is_dir($dir)
            && (file_exists($dir) || !@mkdir($dir, 0o755, true))
        ) {
            throw new PiquleException(
                sprintf('Failed to create directory "%s"', $dir),
            );
        }

        if (!is_writable($dir)) {
            throw new PiquleException(
                sprintf('Directory "%s" is not writable', $dir),
            );
        }

        if (file_put_contents($path, $contents, LOCK_EX) === false) {
            throw new PiquleException(
                sprintf('Failed to write file "%s"', $name),
            );
        }
    }

    private function path(string $name): string
    {
        if ($this->isPathTraversal($name)) {
            throw new PiquleException(
                sprintf('Invalid storage path "%s"', $name),
            );
        }

        return rtrim($this->root, '/') . '/' . $name;
    }

    #[Override]
    public function writeExecutable(string $name, string $contents): void
    {
        $this->write($name, $contents);

        $path = $this->path($name);

        if (!chmod($path, 0o755)) {
            throw new PiquleException(
                sprintf('Failed to chmod file "%s"', $name),
            );
        }
    }

    private function isPathTraversal(string $name): bool
    {
        return str_contains($name, "\0")
            || str_starts_with($name, '/')
            || str_contains($name, '../')
            || str_contains($name, '..\\');
    }
}
