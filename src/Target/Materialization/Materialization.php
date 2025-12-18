<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Target\TargetFile;

interface Materialization
{
    public function applyTo(TargetFile $target): void;
}
