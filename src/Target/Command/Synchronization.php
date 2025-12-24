<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Command;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Source\Sources;
use Haspadar\Piqule\Target\DiskTarget;
use Haspadar\Piqule\Target\TargetStorage;

final readonly class Synchronization implements Command
{
    public function __construct(
        private Sources $sources,
        private TargetStorage $targetDirectory,
        private Output $output,
    ) {}

    public function run(): void
    {
        foreach ($this->sources->files() as $source) {
            $target = new DiskTarget($source, $this->targetDirectory);

            if (!$target->exists()) {
                $message = new Text(
                    sprintf('Created: %s', $target->id()),
                    new Green(),
                );
            } elseif ($target->file()->contents() !== $source->file()->contents()) {
                $message = new Text(
                    sprintf('Updated: %s', $target->id()),
                    new Yellow(),
                );
            } else {
                $message = new Text(
                    sprintf('Skipped: %s', $target->id()),
                    new Grey(),
                );
            }

            $target->materialize();

            $this->output->write($message);
        }
    }
}
