<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Target\FileTarget;
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
    public function writeTo(Storage $storage, FileTarget $target): void
    {
        $storage->writeExecutable(
            $this->name(),
            $this->contents(),
        );
    }
}
