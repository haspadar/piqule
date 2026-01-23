<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\Storage;

interface File
{
    /**
     * Returns the logical name of the file
     */
    public function name(): string;

    /**
     * Returns file contents
     */
    public function contents(): string;

    /**
     * Writes contents to the file
     *
     * @throws PiquleException If writing is not supported
     */
    public function writeTo(Storage $storage): void;
}
