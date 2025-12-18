<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

final readonly class MissingTarget implements TargetState
{
    public function exists(): bool
    {
        return false;
    }

    public function same(): bool
    {
        return false;
    }
}
