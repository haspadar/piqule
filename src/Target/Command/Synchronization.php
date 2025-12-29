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
use Haspadar\Piqule\Target\Storage\TargetStorage;

final readonly class Synchronization implements Command
{
    public function __construct(
        private Sources $sources,
        private TargetStorage $targetDirectory,
        private Output $output,
    ) {}

    #[\Override]
    public function run(): void
    {
        foreach ($this->sources->files() as $source) {
            $target = new DiskTarget($source, $this->targetDirectory);

            if (!$target->exists()) {
                $target->materialize();

                $this->output->write(
                    new Text(
                        sprintf('Created: %s', $target->id()),
                        new Green(),
                    ),
                );
            } elseif ($target->file()->contents() !== $source->file()->contents()) {
                $target->materialize();

                $this->output->write(
                    new Text(
                        sprintf('Updated: %s', $target->id()),
                        new Yellow(),
                    ),
                );
            } else {
                $target->materialize();

                $this->output->write(
                    new Text(
                        sprintf('Skipped: %s', $target->id()),
                        new Grey(),
                    ),
                );
            }
        }
    }
}
