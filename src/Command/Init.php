<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Command;

use FilesystemIterator;
use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Output\Output;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final readonly class Init implements Command
{
    public function __construct(
        private string $source,
        private string $target,
        private FileSystem $fileSystem,
        private Output $output,
    ) {}

    public function run(): void
    {
        (new CopyTemplates(
            $this->source,
            $this->target,
            $this->fileSystem,
            $this->output,
        ))->run();
    }
}
