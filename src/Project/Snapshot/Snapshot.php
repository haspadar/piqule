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
        return $this->hashes->has($target->relativePath());
    }

    public function hashOf(Target $target): string
    {
        return $this->hashes->get($target->relativePath());
    }

    public function hashes(): Hashes
    {
        return $this->hashes;
    }

    public function with(Target $target): Snapshot
    {
        return new self(
            $this->hashes->with(
                $target->relativePath(),
                $target->file()->hash(),
            ),
        );
    }
}
