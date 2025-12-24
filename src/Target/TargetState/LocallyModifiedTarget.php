<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

use Haspadar\Piqule\Target\Target;

final readonly class LocallyModifiedTarget implements TargetState
{
    public function matches(Target $target): bool
    {
        if (!$target->exists() || !$snapshot->has($target->id())) {
            return false;
        }

        return $target->file()->hash() !== $target->source()->file()->hash()
            && $snapshot->hashOf($target->id()) === $target->source()->file()->hash();
    }

    public function name(): string
    {
        return 'locally-modified';
    }
}
