<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

final readonly class ChangedTarget implements TargetState
{
    public function exists(): bool
    {
        return true;
    }

    public function same(): bool
    {
        return false;
    }
}
