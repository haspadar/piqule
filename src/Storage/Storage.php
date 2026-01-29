<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\PiquleException;

interface Storage
{
    /**
     * Checks whether a file with the given logical name exists in this storage
     */
    public function exists(string $name): bool;

    /**
     * Reads contents of a file identified by the given logical name
     *
     * @throws PiquleException if the file cannot be read
     */
    public function read(string $name): string;

    /**
     * Writes contents to a file identified by the given logical name
     *
     * Creates or replaces the file in this storage
     */
    public function write(string $name, string $contents): void;

    /**
     * Writes contents to a file and attempts to make it executable.
     *
     * Implementations MAY:
     * - make the file executable
     * - leave permissions unchanged
     * - throw an exception if unsupported
     *
     * Callers MUST NOT rely on executability being guaranteed.
     */
    public function writeExecutable(string $name, string $contents): void;

    /**
     * Checks whether a file is executable in this storage.
     *
     * Implementations MAY:
     * - return true/false
     * - throw an exception if unsupported
     */
    public function isExecutable(string $name): bool;

    /**
     * @return iterable<string> logical file names
     */
    public function names(): iterable;
}
