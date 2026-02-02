<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class PlacedFile implements File
{
    public function __construct(
        private Storage $storage,
        private string  $folder,
        private string  $name,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function read(): string
    {
        return $this->storage->read($this->path());
    }

    #[Override]
    public function write(string $contents): self
    {
        return new self(
            $this->storage->write($this->path(), $contents),
            $this->folder,
            $this->name,
        );
    }

    public function path(): string
    {
        return $this->folder . '/' . $this->name;
    }
}
