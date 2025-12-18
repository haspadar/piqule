<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\Target\TargetFile;

final readonly class Scenario
{
    /** @param iterable<Step> $steps */
    public function __construct(
        private iterable $steps,
    ) {}

    public function run(TargetFile $target): void
    {
        foreach ($this->steps as $step) {
            $result = $step->applyTo($target);
            if (!$result->shouldContinue()) {
                break;
            }
        }
    }
}
