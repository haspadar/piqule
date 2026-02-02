<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Sources;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Source\DiskSource;
use Override;

final readonly class DirectorySources implements Sources
{
    public function __construct(
        private FileSystem $fs,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->fs->names() as $name) {
            yield new DiskSource(
                $name,
                $this->fs,
            );
        }
    }
}
