<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

final readonly class UnchangedTarget implements TargetState
{
    public function exists(): bool
    {
        return true;
    }

    public function same(): bool
    {
        return true;
    }
}
