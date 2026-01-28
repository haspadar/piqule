<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

interface Storage
{
    /**
     * Checks whether a file with the given logical name exists in this storage
     */
    public function exists(string $name): bool;

    /**
     * Reads contents of a file identified by the given logical name
     *
     * Throws an exception if the file cannot be read
     */
    public function read(string $name): string;

    /**
     * Writes contents to a file identified by the given logical name
     *
     * Creates or replaces the file in this storage
     */
    public function write(string $name, string $contents): void;

    /**
     * Writes contents to a file and makes it executable
     */
    public function writeExecutable(string $name, string $contents): void;

    /**
     * @return iterable<string> logical file names
     */
    public function names(): iterable;
}
