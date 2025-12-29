<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use FilesystemIterator;
use Haspadar\Piqule\File\DiskFile;
use Haspadar\Piqule\PiquleException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final readonly class DiskSources implements Sources
{
    public function __construct(
        private string $path,
    ) {}

    /**
     * @return iterable<Source>
     */
    #[\Override]
    public function files(): iterable
    {
        if (!is_dir($this->path)) {
            throw new PiquleException(
                sprintf('Directory does not exist: "%s"', $this->path),
            );
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->path,
                FilesystemIterator::SKIP_DOTS,
            ),
        );

        /** @var SplFileInfo $item */
        foreach ($iterator as $item) {
            $absolutePath = $item->getPathname();

            $relativePath = ltrim(
                substr($absolutePath, strlen($this->path)),
                DIRECTORY_SEPARATOR,
            );

            yield new Source(
                new DiskFile($absolutePath),
                $relativePath,
            );
        }
    }
}
