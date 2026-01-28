<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class ForcedFile implements File
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
    public function writeTo(Storage $storage, FileReaction $reaction): void
    {
        if (!$storage->exists($this->name())) {
            $this->origin->writeTo($storage, $reaction);
            $reaction->created(new FileCreated($this->name()));

            return;
        }

        if ($storage->read($this->name()) === $this->contents()) {
            $reaction->skipped(new FileSkipped($this->name()));

            return;
        }

        $this->origin->writeTo($storage, $reaction);
        $reaction->updated(new FileUpdated($this->name()));
    }
}
