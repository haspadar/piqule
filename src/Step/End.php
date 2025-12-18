<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\Output\Line\Skipped;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Target\TargetFile;

final readonly class End implements Step
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(TargetFile $target): void
    {
        $this->output->write(new Skipped($target->relativePath()));
    }
}
