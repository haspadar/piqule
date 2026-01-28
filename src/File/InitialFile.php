<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Target\FileTarget;
use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class InitialFile implements File
{
    public function __construct(
        private File $origin,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->origin->name();
    }

    #[Override]
    public function contents(): string
    {
        return $this->origin->contents();
    }

    #[Override]
    public function writeTo(Storage $storage, FileTarget $target): void
    {
        if ($storage->exists($this->name())) {
            $target->skipped(new FileSkipped($this->name()));

            return;
        }

        $this->origin->writeTo($storage, $target);
        $target->created(new FileCreated($this->name()));
    }
}
