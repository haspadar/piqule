<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use FilesystemIterator;
use Haspadar\Piqule\File\File;
use Haspadar\Piqule\PiquleException;
use Override;
use SplFileInfo;

/**
 * Filesystem-backed storage rooted at a given directory
 */
final readonly class DiskStorage implements Storage
{
    public function __construct(
        private string $root,
    ) {}

    /**
     * Reads and returns the file contents at the given location
     *
     * @throws PiquleException
     */
    #[Override]
    public function read(string $location): string
    {
        $path = $this->pathOf($location);

        if (!is_file($path)) {
            throw new PiquleException("Location not found: $location");
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new PiquleException("Unable to read location: $location");
        }

        return $contents;
    }

    /**
     * Recursively yields relative file paths within the given folder
     *
     * @return iterable<string>
     */
    #[Override]
    public function entries(string $location): iterable
    {
        $path = $this->pathOf($location);

        if (!is_dir($path)) {
            return [];
        }

        $iterator = new FilesystemIterator(
            $path,
            FilesystemIterator::SKIP_DOTS,
        );

        foreach ($iterator as $item) {
            /** @var SplFileInfo $item */
            if ($item->isFile()) {
                yield ltrim(
                    $location . '/' . $item->getFilename(),
                    '/',
                );
            }

            if ($item->isDir()) {
                yield from $this->entries(
                    ltrim($location . '/' . $item->getFilename(), '/'),
                );
            }
        }
    }

    /**
     * Checks whether a file exists at the given location
     */
    #[Override]
    public function exists(string $location): bool
    {
        return is_file($this->pathOf($location));
    }

    /**
     * Writes a file to disk, creating parent directories as needed
     *
     * @throws PiquleException
     */
    #[Override]
    public function write(File $file): self
    {
        $location = $file->name();
        $path = $this->pathOf($location);
        $directory = dirname($path);

        if (!is_dir($directory)
            && !mkdir($directory, 0o777, true)
            && !is_dir($directory)
        ) {
            throw new PiquleException("Unable to create directory: $directory");
        }

        if (file_put_contents($path, $file->contents()) === false) {
            throw new PiquleException("Unable to write location: $location");
        }

        if (!chmod($path, $file->mode())) {
            throw new PiquleException("Unable to set permissions: $location");
        }

        return $this;
    }

    /**
     * Returns the file permission bits (masked to 0o777) for the given location
     *
     * @throws PiquleException
     */
    #[Override]
    public function mode(string $location): int
    {
        $path = $this->pathOf($location);

        if (!is_file($path)) {
            throw new PiquleException("Location not found: $location");
        }

        $perms = fileperms($path);

        if ($perms === false) {
            throw new PiquleException("Unable to read permissions: $location");
        }

        return $perms & 0o777;
    }

    private function pathOf(string $location): string
    {
        return (new SafePath($this->root))->resolve($location);
    }
}
