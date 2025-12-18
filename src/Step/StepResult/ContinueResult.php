<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step\StepResult;

final readonly class ContinueResult implements StepResult
{
    public function shouldContinue(): bool
    {
        return true;
    }
}
