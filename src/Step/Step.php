<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\FileSystem\TargetFile;

interface Step
{
    public function applyTo(TargetFile $target): void;
}
