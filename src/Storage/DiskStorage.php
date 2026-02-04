<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use FilesystemIterator;
use Haspadar\Piqule\File\File;
use Haspadar\Piqule\PiquleException;
use Override;
use SplFileInfo;

final readonly class DiskStorage implements Storage
{
    public function __construct(
        private string $root,
    ) {}

    #[Override]
    public function read(string $location): string
    {
        $path = $this->pathOf($location);

        if (!is_file($path)) {
            throw new PiquleException("Location not found: {$location}");
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new PiquleException("Unable to read location: {$location}");
        }

        return $contents;
    }

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

    #[Override]
    public function exists(string $location): bool
    {
        return is_file($this->pathOf($location));
    }

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
            throw new PiquleException("Unable to create directory: {$directory}");
        }

        if (file_put_contents($path, $file->contents()) === false) {
            throw new PiquleException("Unable to write location: {$location}");
        }

        return $this;
    }

    private function pathOf(string $location): string
    {
        return rtrim($this->root, '/')
            . '/'
            . ltrim($location, '/');
    }
}
