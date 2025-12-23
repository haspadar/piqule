<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Snapshot;

interface SnapshotStorage
{
    public function snapshot(): Snapshot;

    public function save(Snapshot $snapshot): void;
}
