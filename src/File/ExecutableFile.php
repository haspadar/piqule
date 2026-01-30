<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class ExecutableFile implements File
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
        $this->origin->writeTo($storage, $reaction);

        if ($storage->isExecutable($this->name())) {
            $reaction->executableAlreadySet($this->name());

            return;
        }

        $storage->writeExecutable($this->name(), $this->contents());
        $reaction->executableWasSet($this->name());
    }
}
