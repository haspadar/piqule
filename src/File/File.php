<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\File\Reaction\FileReaction;
use Haspadar\Piqule\FileSystem\FileSystem;

interface File
{
    /**
     * Returns the file name
     */
    public function name(): string;

    /**
     * Returns file contents
     */
    public function contents(): string;

    /**
     * Writes file to the given filesystem and emits an outcome event
     */
    public function writeTo(FileSystem $fs, FileReaction $reaction): void;
}
