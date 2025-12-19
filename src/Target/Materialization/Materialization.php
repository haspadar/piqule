<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Target\DiskTarget;

interface Materialization
{
    public function applyTo(DiskTarget $target): void;
}
