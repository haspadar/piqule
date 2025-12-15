<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

interface File
{
    public function exists(): bool;

    public function hash(): string;
}
