<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\TargetState;

use Haspadar\Piqule\Project\Snapshot\Snapshot;
use Haspadar\Piqule\Target\Target;

final readonly class ModifiedTarget implements TargetState
{
    public function matches(Target $target, Snapshot $snapshot): bool
    {
        if (!$target->exists() || !$snapshot->has($target->id())) {
            return false;
        }

        return $target->file()->hash() !== $target->source()->file()->hash()
            && $snapshot->hashOf($target->id()) === $target->source()->file()->hash();
    }

    public function name(): string
    {
        return 'modified';
    }
}
