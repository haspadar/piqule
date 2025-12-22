<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Lock;

use Haspadar\Piqule\Target\Target;

interface Lock
{
    public function has(Target $target): bool;

    public function hashOf(Target $target): string;

    public function with(Target $target): Lock;

    public function store(): void;
}
