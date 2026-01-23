<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use FilesystemIterator;
use Haspadar\Piqule\Storage\Storage;
use Override;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final readonly class StoredFiles implements Files
{
    public function __construct(
        private Storage $storage,
        private string $root,
    ) {}

    #[Override]
    public function all(): iterable
    {
        $rootLength = strlen(rtrim($this->root, '/')) + 1;

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->root,
                FilesystemIterator::SKIP_DOTS,
            ),
        );

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            yield new StoredFile(
                substr($file->getPathname(), $rootLength),
                $this->storage,
            );
        }
    }
}
