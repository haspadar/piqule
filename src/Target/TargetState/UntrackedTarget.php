<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

use Haspadar\Piqule\Target\Target;

final readonly class UntrackedTarget implements TargetState
{
    public function matches(Target $target): bool
    {
        return $target->exists()
            && !$snapshot->has($target->id());
    }

    public function name(): string
    {
        return 'untracked';
    }
}
