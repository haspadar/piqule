<?php
declare(strict_types=1);

namespace Haspadar\Piqule\File;

final readonly class ListedFiles implements Files
{
    /**
     * @param list<File> $files
     */
    public function __construct(
        private array $files,
    ) {}

    public function all(): iterable
    {
        return $this->files;
    }
}
