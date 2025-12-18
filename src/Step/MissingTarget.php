<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Step;

use Haspadar\Piqule\Output\Line\Copied;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Target\TargetFile;

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
