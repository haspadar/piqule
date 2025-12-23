<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Project\Snapshot\Snapshot;
use Haspadar\Piqule\Target\Target;

final readonly class Installation implements Materialization
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(Target $target, Snapshot $snapshot): Snapshot
    {
        $target->materialize();
        $this->output->write(
            new Text(
                sprintf('Copied: %s', $target->id()),
                new Green(),
            ),
        );

        return $snapshot->with($target);
    }
}
