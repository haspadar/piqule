<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Application;

use Haspadar\Piqule\File\Files;
use Haspadar\Piqule\File\Target\FileTarget;
use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class FileApplication implements Application
{
    public function __construct(
        private Files $files,
        private Storage $storage,
        private FileTarget $targets,
    ) {}

    #[Override]
    public function run(): void
    {
        foreach ($this->files->all() as $file) {
            $file->writeTo($this->storage, $this->targets);
        }
    }
}
