<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Target\DiskTarget;

final readonly class UpdateMaterialization implements Materialization
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(DiskTarget $target): void
    {
        if (!$target->exists()) {
            $target->materialize();
            $this->output->write(
                new Text(
                    sprintf('Copied: %s', $target->relativePath()),
                    new Green(),
                ),
            );

            return;
        }

        if ($target->hashDiffers()) {
            $target->materialize();
            $this->output->write(
                new Text(
                    sprintf('Updated: %s', $target->relativePath()),
                    new Yellow(),
                ),
            );

            return;
        }

        $this->output->write(
            new Text(
                sprintf('Skipped: %s', $target->relativePath()),
                new Grey(),
            ),
        );
    }
}
