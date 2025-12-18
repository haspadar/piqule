<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\StepResult\StepResult;
use Haspadar\Piqule\Target\TargetFile;

interface Step
{
    public function applyTo(TargetFile $target): StepResult;
}
