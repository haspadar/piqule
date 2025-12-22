<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Snapshot;

use Haspadar\Piqule\Target\Target;

interface Snapshot
{
    public function has(Target $target): bool;

    public function hashOf(Target $target): string;

    public function with(Target $target): Snapshot;

    public function store(): void;
}
