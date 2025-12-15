<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

use Haspadar\Piqule\PiquleException;

final readonly class DiskTargetDirectory implements TargetDirectory
{
    public function __construct(
        private string $root,
    ) {}

    public function exists(string $relativePath): bool
    {
        return is_file($this->root . DIRECTORY_SEPARATOR . $relativePath);
    }

    public function write(string $relativePath, File $source): void
    {
        $target = $this->root . DIRECTORY_SEPARATOR . $relativePath;
        $dir = dirname($target);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0o777, true) && !is_dir($dir)) {
                throw new PiquleException(
                    sprintf('Failed to create directory: "%s"', $dir),
                );
            }
        }

        if (!copy($source->path(), $target)) {
            throw new PiquleException(
                sprintf('Failed to write file: "%s"', $relativePath),
            );
        }
    }
}
