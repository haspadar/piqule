<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Application;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Source\Reaction\FileReaction;
use Haspadar\Piqule\Sources\Sources;
use Override;

final readonly class FileApplication implements Application
{
    public function __construct(
        private Sources      $files,
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
