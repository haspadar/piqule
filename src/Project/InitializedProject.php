<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\Lock\Lock;
use Haspadar\Piqule\Source\SourceDirectory;
use Haspadar\Piqule\Target\Materialization\Materialization;
use Haspadar\Piqule\Target\Target;
use Haspadar\Piqule\Target\TargetDirectory;

final readonly class InitializedProject implements Project
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
    ) {}

    public function init(Materialization $materialization, Lock $lock): void
    {
        throw new PiquleException('Project is already initialized');
    }

    public function update(Materialization $materialization, Lock $lock): void
    {
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $materialization->applyTo(
                new Target($sourceFile, $this->targetDirectory),
                $lock,
            );
        }
    }
}
