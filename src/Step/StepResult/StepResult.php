<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step\StepResult;

interface StepResult
{
    public function shouldContinue(): bool;
}
