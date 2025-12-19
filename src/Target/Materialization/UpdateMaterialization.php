<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Project\Lock\Lock;
use Haspadar\Piqule\Target\Target;

final readonly class UpdateMaterialization implements Materialization
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(Target $target, Lock $lock): Lock
    {
        if (!$target->exists()) {
            $target->materialize();
            $this->output->write(
                new Text(
                    sprintf('Copied: %s', $target->relativePath()),
                    new Green(),
                ),
            );

            return $lock;
        }

        $this->output->write(
            new Text(
                sprintf('Skipped: %s', $target->relativePath()),
                new Grey(),
            ),
        );

        return $lock;
    }
}
