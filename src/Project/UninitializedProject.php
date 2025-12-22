<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\Snapshot\SnapshotStore;
use Haspadar\Piqule\Source\SourceDirectory;
use Haspadar\Piqule\Target\DiskTarget;
use Haspadar\Piqule\Target\Materialization\Materialization;
use Haspadar\Piqule\Target\TargetDirectory;

final readonly class UninitializedProject implements Project
{
    public function __construct(
        private SourceDirectory $sourceDirectory,
        private TargetDirectory $targetDirectory,
        private SnapshotStore $snapshotStore,
    ) {}

    public function init(Materialization $materialization): void
    {
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $snapshot = $materialization->applyTo(
                new DiskTarget($sourceFile, $this->targetDirectory),
                $this->snapshotStore->snapshot(),
            );
        }

        $this->snapshotStore->save($snapshot);
    }

    public function update(Materialization $materialization): void
    {
        throw new PiquleException('Project is not initialized');
    }
}
