<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class LocatedFile implements File
{
    public function __construct(
        private Storage $storage,
        private string $location,
        private string $name,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function read(): string
    {
        return $this->storage->read($this->location);
    }

    #[Override]
    public function write(string $contents): self
    {
        return new self(
            $this->storage->write($this->location, $contents),
            $this->location,
            $this->name,
        );
    }
}
