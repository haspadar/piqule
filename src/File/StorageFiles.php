<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class StorageFiles implements Files
{
    public function __construct(
        private Storage $storage,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->storage->names() as $name) {
            yield new StorageFile(
                $name,
                $this->storage,
            );
        }
    }
}
