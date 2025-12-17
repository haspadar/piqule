<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\FileSystem\TargetFile;
use Haspadar\Piqule\Output\Line\Skipped;
use Haspadar\Piqule\Output\Output;

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
