<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Application;

use Haspadar\Piqule\File\Files;
use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\FileSystem\FileSystem;
use Override;

final readonly class FileApplication implements Application
{
    public function __construct(
        private Files        $files,
        private FileSystem   $fs,
        private FileReaction $reaction,
    ) {}

    #[Override]
    public function run(): void
    {
        foreach ($this->files->all() as $file) {
            $file->writeTo($this->fs, $this->reaction);
        }
    }
}
