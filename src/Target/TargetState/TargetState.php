<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

use Haspadar\Piqule\Project\Snapshot\Snapshot;
use Haspadar\Piqule\Target\Target;

interface TargetState
{
    public function matches(Target $target, Snapshot $snapshot): bool;

    public function name(): string;
}
