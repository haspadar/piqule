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

        if (file_exists($dir) && !is_dir($dir)) {
            throw new PiquleException(
                sprintf('Failed to create directory "%s"', $dir),
            );
        }

        if (!is_dir($dir) && !mkdir($dir, 0o755, true)) {
            throw new PiquleException(
                sprintf('Failed to create directory "%s"', $dir),
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
        return $this->root . '/' . $name;
    }
}
