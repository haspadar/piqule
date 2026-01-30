<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File\Reaction;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Override;

final readonly class ReportingFileReaction implements FileReaction
{
    public function __construct(
        private Output $output,
    ) {}

    #[Override]
    public function created(FileCreated $event): void
    {
        $this->output->write(
            new Text(
                sprintf('Created: %s', $event->name()),
                new Green(),
            ),
        );
    }

    #[Override]
    public function updated(FileUpdated $event): void
    {
        $this->output->write(
            new Text(
                sprintf('Updated: %s', $event->name()),
                new Yellow(),
            ),
        );
    }

    #[Override]
    public function skipped(FileSkipped $event): void
    {
        $this->output->write(
            new Text(
                sprintf('Skipped: %s', $event->name()),
                new Grey(),
            ),
        );
    }

    #[Override]
    public function executableAlreadySet(string $name): void
    {
        $this->output->write(
            new Text(
                sprintf('Already executable: %s', $name),
                new Grey(),
            ),
        );
    }

    #[Override]
    public function executableWasSet(string $name): void
    {
        $this->output->write(
            new Text(
                sprintf('Set executable: %s', $name),
                new Green(),
            ),
        );
    }
}
