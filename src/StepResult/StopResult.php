<?php

declare(strict_types=1);

namespace Haspadar\Piqule\StepResult;

final readonly class StopResult implements StepResult
{
    public function shouldContinue(): bool
    {
        return false;
    }
}
