<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

use FilesystemIterator;
use Haspadar\Piqule\PiquleException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final readonly class DiskDirectory implements Directory
{
    public function __construct(
        private string $path,
    ) {}

    public function exists(): bool
    {
        return is_dir($this->path);
    }

    /**
     * @return iterable<File>
     */
    public function files(): iterable
    {
        if (!$this->exists()) {
            throw new PiquleException(
                sprintf('Directory does not exist: "%s"', $this->path),
            );
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->path,
                FilesystemIterator::SKIP_DOTS,
            ),
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                continue;
            }

            yield new DiskFile($item->getPathname());
        }
    }
}
