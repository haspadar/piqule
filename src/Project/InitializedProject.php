<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\Snapshot\Snapshot;
use Haspadar\Piqule\Source\SourceDirectory;
use Haspadar\Piqule\Target\DiskTarget;
use Haspadar\Piqule\Target\Materialization\Materialization;
use Haspadar\Piqule\Target\TargetDirectory;

final readonly class InitializedProject implements Project
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
    ) {}

    public function init(Materialization $materialization, Snapshot $snapshot): void
    {
        throw new PiquleException('Project is already initialized');
    }

    public function update(Materialization $materialization, Snapshot $snapshot): void
    {
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $snapshot = $materialization->applyTo(
                new DiskTarget($sourceFile, $this->targetDirectory),
                $snapshot,
            );
        }

        $snapshot->store();
    }
}
