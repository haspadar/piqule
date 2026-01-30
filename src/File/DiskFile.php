<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\FileSystem\FileSystem;
use Override;

final readonly class DiskFile implements File
{
    public function __construct(
        private string     $name,
        private FileSystem $fs,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function contents(): string
    {
        return $this->fs->read($this->name);
    }

    #[Override]
    public function writeTo(FileSystem $fs, FileReaction $reaction): void
    {
        $fs->write(
            $this->name,
            $this->contents(),
        );
    }
}
