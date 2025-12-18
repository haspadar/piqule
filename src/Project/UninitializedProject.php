<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Source\SourceDirectory;
use Haspadar\Piqule\Step\Step;
use Haspadar\Piqule\Target\TargetDirectory;
use Haspadar\Piqule\Target\TargetFile;

final readonly class UninitializedProject implements Project
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
        private Step $step,
    ) {}

    public function init(): void
    {
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $this->step->applyTo(
                new TargetFile($sourceFile, $this->targetDirectory),
            );
        }
    }

    public function update(): void
    {
        throw new PiquleException('Project is not initialized');
    }
}
