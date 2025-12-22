<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\Lock\Lock;
use Haspadar\Piqule\Source\SourceDirectory;
use Haspadar\Piqule\Target\DiskTarget;
use Haspadar\Piqule\Target\Materialization\Materialization;
use Haspadar\Piqule\Target\TargetDirectory;

final readonly class UninitializedProject implements Project
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
    ) {}

    public function init(Materialization $materialization, Lock $lock): void
    {
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $lock = $materialization->applyTo(
                new DiskTarget($sourceFile, $this->targetDirectory),
                $lock,
            );
        }

        $lock->store();
    }

    public function update(Materialization $materialization, Lock $lock): void
    {
        throw new PiquleException('Project is not initialized');
    }
}
