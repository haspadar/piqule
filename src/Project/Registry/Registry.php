<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Registry;

use Haspadar\Piqule\Target\Target;

interface Registry
{
    public function knows(Target $target): bool;

    public function isUnchanged(Target $target): bool;

    public function withRemembered(Target $target): Registry;

    public function store(): void;
}
