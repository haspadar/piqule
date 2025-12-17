<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

use Haspadar\Piqule\FileSystem\SourceDirectory;
use Haspadar\Piqule\FileSystem\TargetDirectory;
use Haspadar\Piqule\FileSystem\TargetFile;
use Haspadar\Piqule\Step\Step;

final readonly class Init
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
        private Step $step,
    ) {}

    /** @return list<string> */
    public function run(): array
    {
        $copied = [];
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $this->step->applyTo(
                new TargetFile($sourceFile, $this->targetDirectory),
            );
        }

        return $copied;
    }
}
