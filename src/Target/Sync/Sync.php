<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Sync;

use Haspadar\Piqule\Target\Storage\TargetStorage;

interface Sync
{
    public function apply(TargetStorage $targetStorage): void;
}
