<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

use FilesystemIterator;
use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Output\Line\Copied;
use Haspadar\Piqule\Output\Line\Skipped;
use Haspadar\Piqule\Output\Output;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final readonly class CopyTemplates
{
    public function __construct(
        private string $source,
        private string $target,
        private FileSystem $fileSystem,
        private Output $output,
    ) {}

    /** @return list<CopiedFile> */
    public function run(): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->source,
                FilesystemIterator::SKIP_DOTS,
            ),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        $copied = [];
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                continue;
            }

            $relative = $iterator->getSubPathName();
            $target = $this->target . DIRECTORY_SEPARATOR . $relative;
            if ($this->fileSystem->exists($target)) {
                $this->output->write(
                    new Skipped("$relative already exists"),
                );
                continue;
            }

            $this->fileSystem->ensureDirectory($target);
            $this->fileSystem->copy($item->getPathname(), $target);
            $copied[] = new CopiedFile($relative, $target, $item->getPathname());
            $this->output->write(new Copied($relative));
        }

        return $copied;
    }
}
