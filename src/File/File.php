<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Target\FileTarget;
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
     * Writes file to target storage and emits an outcome event
     */
    public function writeTo(Storage $storage, FileTarget $target): void;
}
