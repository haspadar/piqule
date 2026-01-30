<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\FileSystem\FileSystem;
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
    public function writeTo(FileSystem $fs, FileReaction $reaction): void
    {
        if ($fs->exists($this->name())) {
            $reaction->skipped(new FileSkipped($this->name()));

            return;
        }

        $this->origin->writeTo($fs, $reaction);
        $reaction->created(new FileCreated($this->name()));
    }
}
