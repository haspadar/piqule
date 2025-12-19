<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\File\DiskFile;
use Haspadar\Piqule\File\File;
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
            $this->createDirectory($dir);
        }

        if (file_put_contents($target, $source->contents()) === false) {
            throw new PiquleException(
                sprintf('Failed to write file: "%s"', $relativePath),
            );
        }
    }

    public function read(string $relativePath): File
    {
        return new DiskFile(
            $this->root . DIRECTORY_SEPARATOR . $relativePath,
        );
    }

    /**
     * @param string $dir
     */
    public function createDirectory(string $dir): void
    {
        if (!mkdir($dir, 0o755, true) && !is_dir($dir)) {
            throw new PiquleException(
                sprintf('Failed to create directory: "%s"', $dir),
            );
        }
    }
}
