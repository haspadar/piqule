<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File\Target;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Override;

final readonly class Targets implements FileTarget
{
    /**
     * @param list<FileTarget> $targets
     */
    public function __construct(
        private array $targets,
    ) {}

    #[Override]
    public function created(FileCreated $event): void
    {
        foreach ($this->targets as $target) {
            $target->created($event);
        }
    }

    public function updated(FileUpdated $event): void
    {
        foreach ($this->targets as $target) {
            $target->updated($event);
        }
    }

    public function skipped(FileSkipped $event): void
    {
        foreach ($this->targets as $target) {
            $target->skipped($event);
        }
    }
}
