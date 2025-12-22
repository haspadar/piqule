<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Project\Snapshot\Snapshot;
use Haspadar\Piqule\Target\Materialization\Materialization;

interface Project
{
    public function init(Materialization $materialization, Snapshot $snapshot): void;

    public function update(Materialization $materialization, Snapshot $snapshot): void;
}
