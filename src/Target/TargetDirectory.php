<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\Source\File;

interface TargetDirectory
{
    /**
     * Returns true if a file exists at the given relative path
     */
    public function exists(string $relativePath): bool;

    /**
     * Writes source file contents to the target location,
     * creating parent directories if needed
     */
    public function write(string $relativePath, File $source): void;
}
