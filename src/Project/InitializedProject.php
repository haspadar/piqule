<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\Snapshot\SnapshotStorage;
use Haspadar\Piqule\Source\Sources;
use Haspadar\Piqule\Target\DiskTarget;
use Haspadar\Piqule\Target\Materialization\Materialization;
use Haspadar\Piqule\Target\TargetStorage;

final readonly class InitializedProject implements Project
{
    public function __construct(
        private Sources         $sourceDirectory,
        private TargetStorage   $targetDirectory,
        private SnapshotStorage $snapshotStore,
    ) {}

    public function init(Materialization $materialization): void
    {
        throw new PiquleException('Project is already initialized');
    }

    public function update(Materialization $materialization): void
    {
        foreach ($this->sourceDirectory->files() as $sourceFile) {
            $snapshot = $materialization->applyTo(
                new DiskTarget($sourceFile, $this->targetDirectory),
                $this->snapshotStore->snapshot(),
            );
        }

        $this->snapshotStore->save($snapshot);
    }
}
