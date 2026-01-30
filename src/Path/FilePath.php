<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path;

use Haspadar\Piqule\File\FileName;
use Override;

final readonly class FilePath implements Path
{
    public function __construct(
        private DirectoryPath $directory,
        private FileName $name,
    ) {}

    #[Override]
    public function value(): string
    {
        $directory = $this->directory->value();
        $name = $this->name->value();

        return $directory . '/' . $name;
    }
}
