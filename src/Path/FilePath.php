<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path;

use Haspadar\Piqule\File\DirectoryPath;
use Haspadar\Piqule\File\FileName;
use Haspadar\Piqule\PiquleException;
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

        if ($this->endsWithSeparator($name)) {
            throw new PiquleException('File path must point to a file, not a directory');
        }

        return $directory . '/' . $name;
    }

    private function endsWithSeparator(string $name): bool
    {
        return str_ends_with($name, '/') || str_ends_with($name, '\\');
    }
}
