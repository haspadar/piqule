<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Project\Lock\Lock;
use Haspadar\Piqule\Target\Target;

final readonly class Synchronization implements Materialization
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(Target $target, Lock $lock): Lock
    {
        if (!$target->exists()) {
            return $this->copy($target, $lock);
        }

        if ($this->isUpToDate($target)) {
            $this->output->write(
                new Text(
                    sprintf('Skipped: %s', $target->relativePath()),
                    new Grey(),
                ),
            );

            return $lock;
        }

        if ($this->canOverwrite($target, $lock)) {
            return $this->synchronize($target, $lock);
        }

        return $this->update($target, $lock);
    }

    private function copy(Target $target, Lock $lock): Lock
    {
        $target->materialize();
        $this->output->write(new Text(
            sprintf('Copied: %s', $target->relativePath()),
            new Green(),
        ));

        return $lock->with($target);
    }

    private function synchronize(Target $target, Lock $lock): Lock
    {
        $target->materialize();
        $this->output->write(new Text(
            sprintf('Synchronized: %s', $target->relativePath()),
            new Green(),
        ));

        return $lock->with($target);
    }

    private function update(Target $target, Lock $lock): Lock
    {
        $target->materialize();
        $this->output->write(new Text(
            sprintf('Updated: %s', $target->relativePath()),
            new Green(),
        ));

        return $lock->with($target);
    }

    private function canOverwrite(Target $target, Lock $lock): bool
    {
        return $lock->has($target)
            && $target->exists()
            && $lock->hashOf($target) === $target->file()->hash();
    }

    private function isUpToDate(Target $target): bool
    {
        return $target->exists()
            && $target->file()->hash() === $target->sourceFile()->hash();
    }
}
