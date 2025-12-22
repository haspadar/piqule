<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Project\Lock\Lock;
use Haspadar\Piqule\Target\Target;

interface Materialization
{
    public function applyTo(Target $target, Lock $lock): Lock;
}
