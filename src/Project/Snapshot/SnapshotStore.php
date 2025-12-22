<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Snapshot;

interface SnapshotStore
{
    public function snapshot(): Snapshot;

    public function save(Snapshot $snapshot): void;
}
