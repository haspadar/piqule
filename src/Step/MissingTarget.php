<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\FileSystem\TargetFile;
use Haspadar\Piqule\Output\Line\Copied;
use Haspadar\Piqule\Output\Output;

final readonly class MissingTarget implements Step
{
    public function __construct(
        private Output $output,
        private Step $next,
    ) {}

    public function applyTo(TargetFile $target): void
    {
        if (!$target->exists()) {
            $target->materialize();
            $this->output->write(new Copied($target->relativePath()));

            return;
        }

        $this->next->applyTo($target);
    }
}
