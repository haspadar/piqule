<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\File\File;

interface Target
{
    public function exists(): bool;

    public function relativePath(): string;

    public function file(): File;

    public function sourceFile(): File;
}
