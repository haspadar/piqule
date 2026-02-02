<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Source\Reaction\FileReaction;
use Override;

final readonly class ExecutableSource implements Source
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
        $this->origin->writeTo($fs, $reaction);

        if ($fs->isExecutable($this->name())) {
            $reaction->executableAlreadySet($this->name());

            return;
        }

        $fs->writeExecutable($this->name(), $this->contents());
        $reaction->executableWasSet($this->name());
    }
}
