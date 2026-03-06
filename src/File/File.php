<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

/**
 * A named file with content and POSIX permissions
 */
interface File
{
    /**
     * Relative path inside storage
     */
    public function name(): string;

    /**
     * Raw file content
     */
    public function contents(): string;

    /**
     * POSIX file permission bits (e.g., 0o644, 0o755)
     */
    public function mode(): int;
}
