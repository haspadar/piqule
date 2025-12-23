<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Snapshot;

use Haspadar\Piqule\Project\Hashes;
use Haspadar\Piqule\Target\Target;

final readonly class Snapshot
{
    public function __construct(private Hashes $hashes) {}

    public function has(Target $target): bool
    {
        return $this->hashes->has($target->id());
    }

    public function hashOf(Target $target): string
    {
        return $this->hashes->get($target->id());
    }

    public function toArray(): array
    {
        return $this->hashes->values();
    }

    public function with(Target $target): Snapshot
    {
        return new self(
            $this->hashes->with(
                $target->id(),
                $target->file()->hash(),
            ),
        );
    }
}
