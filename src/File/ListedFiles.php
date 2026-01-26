<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Override;

final readonly class ListedFiles implements Files
{
    /**
     * @param list<File> $files
     */
    public function __construct(
        private array $files,
    ) {}

    #[Override]
    public function all(): iterable
    {
        return $this->files;
    }
}
