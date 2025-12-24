<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

use Haspadar\Piqule\Target\Target;

final readonly class UnchangedTarget implements TargetState
{
    public function matches(Target $target): bool
    {
        if (!$target->exists() || !$snapshot->has($target->id())) {
            return false;
        }

        return $snapshot->hashOf($target->id()) === $target->file()->hash()
            && $target->file()->hash() === $target->source()->file()->hash();
    }

    public function name(): string
    {
        return 'unchanged';
    }
}
