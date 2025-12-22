<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Materialization;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Project\Snapshot\Snapshot;
use Haspadar\Piqule\Target\Target;

final readonly class Synchronization implements Materialization
{
    public function __construct(
        private Output $output,
    ) {}

    public function applyTo(Target $target, Snapshot $snapshot): Snapshot
    {
        if (!$target->exists()) {
            return $this->copy($target, $snapshot);
        }

        if ($this->isUpToDate($target, $snapshot)) {
            $this->output->write(
                new Text(
                    sprintf('Skipped: %s', $target->relativePath()),
                    new Grey(),
                ),
            );

            return $snapshot;
        }

        if ($this->canOverwrite($target, $snapshot)) {
            return $this->synchronize($target, $snapshot);
        }

        return $this->update($target, $snapshot);
    }

    private function copy(Target $target, Snapshot $snapshot): Snapshot
    {
        return $this->materializeWithMessage($target, $snapshot, 'Copied');
    }

    private function synchronize(Target $target, Snapshot $snapshot): Snapshot
    {
        return $this->materializeWithMessage($target, $snapshot, 'Synchronized');
    }

    private function update(Target $target, Snapshot $snapshot): Snapshot
    {
        return $this->materializeWithMessage($target, $snapshot, 'Updated');
    }

    private function materializeWithMessage(
        Target   $target,
        Snapshot $snapshot,
        string   $message,
    ): Snapshot {
        $target->materialize();
        $this->output->write(
            new Text(
                sprintf('%s: %s', $message, $target->relativePath()),
                new Green(),
            ),
        );

        return $snapshot->with($target);
    }

    private function canOverwrite(Target $target, Snapshot $snapshot): bool
    {
        return $snapshot->has($target)
            && $target->exists()
            && $snapshot->hashOf($target) === $target->file()->hash();
    }

    private function isUpToDate(Target $target, Snapshot $snapshot): bool
    {
        if (!$target->exists()) {
            return false;
        }

        if (!$snapshot->has($target)) {
            return false;
        }

        return $snapshot->hashOf($target) === $target->file()->hash();
    }
}
