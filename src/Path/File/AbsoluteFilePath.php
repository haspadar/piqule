<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path\File;

use Haspadar\Piqule\File\FileName;
use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Override;

final readonly class AbsoluteFilePath implements FilePath
{
    public function __construct(
        private AbsoluteDirectoryPath $directory,
        private FileName              $name,
    ) {}

    #[Override]
    public function value(): string
    {
        $directory = $this->directory->value();
        $name = $this->name->value();

        return $directory . '/' . $name;
    }
}
