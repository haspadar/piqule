<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage\Reaction;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Override;

final readonly class ReportingStorageReaction implements StorageReaction
{
    public function __construct(
        private Output $output,
    ) {}

    #[Override]
    public function created(string $path): void
    {
        $this->output->write(
            new Text(
                sprintf('Created: %s', $path),
                new Green(),
            ),
        );
    }

    #[Override]
    public function updated(string $path): void
    {
        $this->output->write(
            new Text(
                sprintf('Updated: %s', $path),
                new Yellow(),
            ),
        );
    }
}
