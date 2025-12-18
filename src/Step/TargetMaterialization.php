<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Step\StepResult\ContinueResult;
use Haspadar\Piqule\Step\StepResult\StepResult;
use Haspadar\Piqule\Target\TargetFile;

final readonly class TargetMaterialization implements Step
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(TargetFile $target): StepResult
    {
        $state = $target->state();
        if (!$state->exists() || !$state->same()) {
            $target->materialize();
            $this->output->write(
                new Text(
                    sprintf('Copied: %s', $target->relativePath()),
                    new Green(),
                ),
            );

            return new ContinueResult();
        }

        return new ContinueResult();
    }
}
