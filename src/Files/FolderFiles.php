<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Haspadar\Piqule\File\PlacedFile;
use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class FolderFiles implements Files
{
    public function __construct(
        private Storage $storage,
        private string $folder,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->storage->entries($this->folder) as $path) {
            yield new PlacedFile(
                $this->storage,
                dirname($path),
                basename($path),
            );
        }
    }
}
