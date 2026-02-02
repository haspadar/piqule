<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Source\Event\FileCreated;
use Haspadar\Piqule\Source\Event\FileSkipped;
use Haspadar\Piqule\Source\Reaction\FileReaction;
use Override;

final readonly class InitialSource implements Source
{
    public function __construct(
        private Source $origin,
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
