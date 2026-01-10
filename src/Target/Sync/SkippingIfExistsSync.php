<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Sync;

use Haspadar\Piqule\Output\Color\Green;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Source\Sources;
use Haspadar\Piqule\Target\DiskTarget;
use Haspadar\Piqule\Target\Storage\TargetStorage;
use Override;

final readonly class SkippingIfExistsSync implements Sync
{
    public function __construct(
        private Sources $sources,
        private TargetStorage $targetStorage,
        private Output $output,
    ) {}

    #[Override]
    public function apply(): void
    {
        foreach ($this->sources->files() as $source) {
            $target = new DiskTarget($source, $this->targetStorage);

            if ($target->exists()) {
                $this->output->write(
                    new Text(
                        sprintf('Skipped: %s', $target->id()),
                        new Grey(),
                    ),
                );
                continue;
            }

            $target->materialize();

            $this->output->write(
                new Text(
                    sprintf('Created: %s', $target->id()),
                    new Green(),
                ),
            );
        }
    }
}
