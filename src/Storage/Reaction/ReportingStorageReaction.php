<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage\Reaction;

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
        $this->output->success(
            sprintf('Created: %s', $path),
        );
    }

    #[Override]
    public function updated(string $path): void
    {
        $this->output->info(
            sprintf('Updated: %s', $path),
        );
    }
}
