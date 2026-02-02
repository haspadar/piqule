<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Source\Reaction\FileReaction;
use Override;

final readonly class PlacedSource implements Source
{
    public function __construct(
        private Source $origin,
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
