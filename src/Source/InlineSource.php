<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Source\Reaction\FileReaction;
use Override;

final readonly class InlineSource implements Source
{
    public function __construct(
        private string $name,
        private string $contents,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function contents(): string
    {
        return $this->contents;
    }

    #[Override]
    public function writeTo(FileSystem $fs, FileReaction $reaction): void
    {
        $fs->write(
            $this->name,
            $this->contents,
        );
    }
}
