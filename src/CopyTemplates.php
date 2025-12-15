<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

use Haspadar\Piqule\FileSystem\SourceDirectory;
use Haspadar\Piqule\FileSystem\TargetDirectory;
use Haspadar\Piqule\Output\Line\Copied;
use Haspadar\Piqule\Output\Line\Skipped;
use Haspadar\Piqule\Output\Output;

final readonly class CopyTemplates
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
        private Output $output,
    ) {}

    /** @return list<string> */
    public function run(): array
    {
        $copied = [];

        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $relativePath = $sourceFile->relativePath();

            if ($this->targetDirectory->exists($relativePath)) {
                $this->output->write(
                    new Skipped(sprintf('%s already exists', $relativePath)),
                );
                continue;
            }

            $this->targetDirectory->write($relativePath, $sourceFile->file());

            $copied[] = $relativePath;
            $this->output->write(new Copied($relativePath));
        }

        return $copied;
    }
}
