<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\Storage;
use Override;

/**
 * Reads all files from a given storage folder as a Files collection
 */
final readonly class FolderFiles implements Files
{
    /**
     * @param Storage $storage Source storage to read files from
     * @param string $folder Folder path relative to storage root
     */
    public function __construct(
        private Storage $storage,
        private string $folder,
    ) {}

    /**
     * @return iterable<TextFile>
     */
    #[Override]
    public function all(): iterable
    {
        foreach ($this->storage->entries($this->folder) as $path) {
            yield new TextFile(
                $path,
                $this->storage->read($path),
                $this->storage->mode($path),
            );
        }
    }
}
