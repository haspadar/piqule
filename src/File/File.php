<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

interface File
{
    /**
     * Relative path inside storage
     */
    public function name(): string;

    public function contents(): string;

    /**
     * POSIX file permission bits (e.g., 0o644, 0o755)
     */
    public function mode(): int;
}
