<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

use Haspadar\Piqule\FileSystem\SourceDirectory;
use Haspadar\Piqule\FileSystem\TargetDirectory;
use Haspadar\Piqule\Output\Output;

final readonly class Init
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
        private Output $output,
    ) {}

    public function run(): void
    {
        (new CopyTemplates(
            $this->sourceDirectory,
            $this->targetDirectory,
            $this->output,
        ))->run();
    }
}
