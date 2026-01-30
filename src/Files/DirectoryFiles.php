<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Haspadar\Piqule\File\DiskFile;
use Haspadar\Piqule\FileSystem\FileSystem;
use Override;

final readonly class DirectoryFiles implements Files
{
    public function __construct(
        private FileSystem $fs,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->fs->names() as $name) {
            yield new DiskFile(
                $name,
                $this->fs,
            );
        }
    }
}
