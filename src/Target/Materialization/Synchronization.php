<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Project\Registry\Registry;
use Haspadar\Piqule\Target\Target;

final readonly class Synchronization implements Materialization
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(Target $target, Registry $lock): Registry
    {
        if (!$target->exists()) {
            $target->materialize();
            $this->output->write(
                new Text(
                    sprintf('Copied: %s', $target->relativePath()),
                    new Green(),
                ),
            );

            return $lock->withRemembered($target);
        }

        if ($lock->isUnchanged($target)) {
            $target->materialize();
            $this->output->write(
                new Text(
                    sprintf('Synchronized: %s', $target->relativePath()),
                    new Green(),
                ),
            );

            return $lock->withRemembered($target);
        }

        $target->materialize();
        $this->output->write(
            new Text(
                sprintf('Updated: %s', $target->relativePath()),
                new Green(),
            ),
        );

        return $lock->withRemembered($target);
    }
}
