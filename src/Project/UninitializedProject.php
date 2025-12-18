<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Source\SourceDirectory;
use Haspadar\Piqule\Step\Scenario;
use Haspadar\Piqule\Target\TargetDirectory;
use Haspadar\Piqule\Target\TargetFile;

final readonly class UninitializedProject implements Project
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
    ) {}

    public function init(Scenario $scenario): void
    {
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $scenario->run(
                new TargetFile($sourceFile, $this->targetDirectory),
            );
        }
    }

    public function update(): void
    {
        throw new PiquleException('Project is not initialized');
    }
}
