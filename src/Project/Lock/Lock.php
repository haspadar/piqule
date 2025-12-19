<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Lock;

use Haspadar\Piqule\Target\Target;

interface Lock
{
    public function knows(Target $target): bool;

    public function isUnchanged(Target $target): bool;

    public function withRemembered(Target $target): Lock;

    public function store(): void;
}
