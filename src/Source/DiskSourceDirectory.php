<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use FilesystemIterator;
use Haspadar\Piqule\PiquleException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final readonly class DiskSourceDirectory implements SourceDirectory
{
    public function __construct(
        private string $path,
    ) {}

    /**
     * @return iterable<SourceFile>
     */
    public function files(): iterable
    {
        if (!is_dir($this->path)) {
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

            /**
             * @var $directoryIterator RecursiveDirectoryIterator
             */
            $directoryIterator = $iterator->getSubIterator();
            yield new SourceFile(
                new DiskFile($item->getPathname()),
                $directoryIterator->getSubPathName(),
            );
        }
    }
}
