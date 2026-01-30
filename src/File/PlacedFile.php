<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\FileSystem\FileSystem;
use Override;

final readonly class PlacedFile implements File
{
    public function __construct(
        private File $origin,
        private string $name,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function contents(): string
    {
        return $this->origin->contents();
    }

    #[Override]
    public function writeTo(FileSystem $fs, FileReaction $reaction): void
    {
        $fs->write(
            $this->name(),
            $this->contents(),
        );
    }
}
