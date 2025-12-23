<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Project\Snapshot\SnapshotStorage;
use Haspadar\Piqule\Source\Sources;
use Haspadar\Piqule\Target\DiskTarget;
use Haspadar\Piqule\Target\Materialization\Materialization;
use Haspadar\Piqule\Target\TargetStorage;

final readonly class InitializedProject implements Project
{
    public function __construct(
        private Sources         $sources,
        private TargetStorage   $targetDirectory,
        private SnapshotStorage $snapshotStore,
    ) {}

    public function sync(Materialization $materialization): void
    {
        $snapshot = $this->snapshotStore->snapshot();

        foreach ($this->sources->files() as $sourceFile) {
            $snapshot = $materialization->applyTo(
                new DiskTarget($sourceFile, $this->targetDirectory),
                $snapshot,
            );
        }

        $this->snapshotStore->save($snapshot);
    }
}
