<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Command;

use FilesystemIterator;
use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Output\Line\Copied;
use Haspadar\Piqule\Output\Line\Skipped;
use Haspadar\Piqule\Output\Output;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final readonly class CopyTemplates implements Command
{
    public function __construct(
        private string $source,
        private string $target,
        private FileSystem $fileSystem,
        private Output $output,
    ) {}

    public function run(): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->source,
                FilesystemIterator::SKIP_DOTS,
            ),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($iterator as $item) {
            $relative = substr($item->getPathname(), strlen($this->source) + 1);
            $target = $this->target . DIRECTORY_SEPARATOR . $relative;
            if ($item->isDir()) {
                $this->fileSystem->ensureDirectory($target);
            } else {
                $this->copyFile($item->getPathname(), $target, $relative);

            }
        }
    }

    private function copyFile(string $source, string $target, string $relative): void
    {
        if ($this->fileSystem->exists($target)) {
            $this->output->write(
                new Skipped("$relative already exists"),
            );

            return;
        }

        $this->fileSystem->copy($source, $target);
        $this->output->write(new Copied($relative));
    }
}
