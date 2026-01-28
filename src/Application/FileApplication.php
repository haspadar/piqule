<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Application;

use Haspadar\Piqule\File\Files;
use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class FileApplication implements Application
{
    public function __construct(
        private Files        $files,
        private Storage      $storage,
        private FileReaction $reaction,
    ) {}

    #[Override]
    public function run(): void
    {
        foreach ($this->files->all() as $file) {
            $file->writeTo($this->storage, $this->reaction);
        }
    }
}
