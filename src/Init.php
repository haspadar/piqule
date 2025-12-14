<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Output\Output;

final readonly class Init
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
