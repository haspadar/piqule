<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Command;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Output\Line\Copied;
use Haspadar\Piqule\Output\Line\Skipped;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Structure\Root;

final readonly class Init implements Command
{
    public function __construct(
        private Root $root,
        private FileSystem $fileSystem,
        private Output $output,
    ) {}

    public function run(): void
    {
        $dotPiqule = $this->root->dotPiqule();
        if ($this->fileSystem->exists($dotPiqule->path())) {
            $this->output->write(new Skipped('.piqule already exists'));

            return;
        }

        $this->fileSystem->createDirectory($dotPiqule->path());
        $this->output->write(new Copied('.piqule'));
    }
}
