<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Target\TargetFile;

final readonly class InitMaterialization implements Materialization
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(TargetFile $target): void
    {
        $target->materialize();
        $this->output->write(
            new Text(
                sprintf('Copied: %s', $target->relativePath()),
                new Green(),
            ),
        );
    }
}
