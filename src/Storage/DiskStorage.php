<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use FilesystemIterator;
use Haspadar\Piqule\PiquleException;
use Override;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final readonly class DiskStorage implements Storage
{
    public function __construct(
        private DiskPath $path,
    ) {}

    #[Override]
    public function exists(string $name): bool
    {
        return is_file($this->path->full($name));
    }

    #[Override]
    public function read(string $name): string
    {
        $path = $this->path->full($name);

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
        $path = $this->path->full($name);
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

    #[Override]
    public function writeExecutable(string $name, string $contents): void
    {
        $this->write($name, $contents);

        $path = $this->path->full($name);

        if (!chmod($path, 0o755)) {
            throw new PiquleException(
                sprintf('Failed to chmod file "%s"', $name),
            );
        }
    }

    #[Override]
    public function names(): iterable
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->path->root(),
                FilesystemIterator::SKIP_DOTS,
            ),
        );

        $rootLength = strlen(rtrim($this->path->root(), '/')) + 1;

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            yield substr($file->getPathname(), $rootLength);
        }
    }
}
