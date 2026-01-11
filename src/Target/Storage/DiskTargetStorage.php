<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Storage;

use Haspadar\Piqule\File\DiskFile;
use Haspadar\Piqule\File\File;
use Haspadar\Piqule\PiquleException;
use Override;

final readonly class DiskTargetStorage implements TargetStorage
{
    public function __construct(
        private string $root,
    ) {
    }

    #[Override]
    public function exists(string $relativePath): bool
    {
        return is_file($this->root . '/' . $relativePath);
    }

    #[Override]
    public function write(string $relativePath, File $source): void
    {
        $target = $this->root . '/' . $relativePath;
        $dir = dirname($target);
        $this->createDirectory($dir);

        if (file_put_contents($target, $source->contents()) === false) {
            throw new PiquleException(
                sprintf('Failed to write file: "%s"', $relativePath),
            );
        }
    }

    #[Override]
    public function read(string $relativePath): File
    {
        return new DiskFile(
            $this->root . '/' . $relativePath,
        );
    }

    private function createDirectory(string $dir): void
    {
        if (is_dir($dir)) {
            return;
        }

        if (file_exists($dir)) {
            throw new PiquleException(
                sprintf('Failed to create directory: "%s"', $dir),
            );
        }

        if (!mkdir($dir, 0o755, true)) {
            throw new PiquleException(
                sprintf('Failed to create directory: "%s"', $dir),
            );
        }
    }
}
