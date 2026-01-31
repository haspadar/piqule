<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

use FilesystemIterator;
use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Haspadar\Piqule\PiquleException;
use Override;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final readonly class DiskFileSystem implements FileSystem
{
    public function __construct(
        private AbsoluteDirectoryPath $root,
    ) {}

    #[Override]
    public function exists(string $name): bool
    {
        return is_file($this->fullPath($name));
    }

    #[Override]
    public function read(string $name): string
    {
        $path = $this->fullPath($name);

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
        $path = $this->fullPath($name);
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

        $path = $this->fullPath($name);

        if (!chmod($path, 0o755)) {
            throw new PiquleException(
                sprintf('Failed to chmod file "%s"', $name),
            );
        }
    }

    #[Override]
    public function isExecutable(string $name): bool
    {
        $path = $this->fullPath($name);

        if (!is_file($path)) {
            throw new PiquleException(
                sprintf('File "%s" does not exist', $name),
            );
        }

        return is_executable($path);
    }

    #[Override]
    public function names(): iterable
    {
        $root = $this->root->value();
        $rootLength = strlen(rtrim($root, DIRECTORY_SEPARATOR)) + 1;

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $root,
                FilesystemIterator::SKIP_DOTS,
            ),
        );

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            yield substr($file->getPathname(), $rootLength);
        }
    }

    private function fullPath(string $name): string
    {
        return rtrim($this->root->value(), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . $name;
    }
}
