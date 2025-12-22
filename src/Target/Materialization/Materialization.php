<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Project\Registry\Registry;
use Haspadar\Piqule\Target\Target;

interface Materialization
{
    public function applyTo(Target $target, Registry $lock): Registry;
}
