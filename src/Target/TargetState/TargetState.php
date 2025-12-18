<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

interface TargetState
{
    public function exists(): bool;

    public function same(): bool;
}
