<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class ForcedFile implements File
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
    public function writeTo(Storage $storage): void
    {
        if ($storage->exists($this->name())
            && $storage->read($this->name()) === $this->contents()
        ) {
            return;
        }

        $this->origin->writeTo($storage);
    }
}
