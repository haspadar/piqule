<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage\Reaction;

use Haspadar\Piqule\Output\Output;
use Override;

/**
 * Reports created and updated storage events to an Output channel.
 */
final readonly class ReportingStorageReaction implements StorageReaction
{
    /** Initializes with the output channel for reporting. */
    public function __construct(private Output $output) {}

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
