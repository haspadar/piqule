<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

use Haspadar\Piqule\Target\Target;

interface TargetState
{
    public function matches(Target $target): bool;

    public function name(): string;
}
