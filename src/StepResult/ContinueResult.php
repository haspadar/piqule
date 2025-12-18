<?php

declare(strict_types=1);

namespace Haspadar\Piqule\StepResult;

final readonly class ContinueResult implements StepResult
{
    public function shouldContinue(): bool
    {
        return true;
    }
}
