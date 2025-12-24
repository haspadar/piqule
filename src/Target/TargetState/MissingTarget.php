<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

use Haspadar\Piqule\Target\Target;

final readonly class MissingTarget implements TargetState
{
    public function matches(Target $target): bool
    {
        return !$target->exists();
    }

    public function name(): string
    {
        return 'missing';
    }
}
