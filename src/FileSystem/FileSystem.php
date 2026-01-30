<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

use Haspadar\Piqule\PiquleException;

interface FileSystem
{
    /**
     * Checks whether a file exists at the given path
     */
    public function exists(string $name): bool;

    /**
     * Reads contents of a file at the given path
     *
     * @throws PiquleException if the file cannot be read
     */
    public function read(string $name): string;

    /**
     * Writes contents to a file at the given path
     *
     * Creates or replaces the file
     */
    public function write(string $name, string $contents): void;

    /**
     * Writes contents to a file and attempts to make it executable
     *
     * Implementations MAY:
     * - make the file executable
     * - leave permissions unchanged
     * - throw an exception if unsupported
     *
     * Callers MUST NOT rely on executability being guaranteed
     */
    public function writeExecutable(string $name, string $contents): void;

    /**
     * Checks whether a file at the given path is executable
     *
     * Implementations MAY:
     * - return true or false
     * - throw an exception if unsupported
     *
     * @throws PiquleException if the file does not exist
     */
    public function isExecutable(string $name): bool;

    /**
     * Returns file names available in this filesystem scope
     *
     * Intended for directory traversal
     *
     * @return iterable<string>
     */
    public function names(): iterable;
}
