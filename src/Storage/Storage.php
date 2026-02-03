<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\PiquleException;

interface Storage
{
    /**
     * Reads contents from the given location
     *
     * @throws PiquleException if the location does not exist
     */
    public function read(string $location): string;

    /**
     * Persists the given file into this storage
     */
    public function write(File $file): self;

    /**
     * Checks whether a projection exists at the given location
     */
    public function exists(string $location): bool;

    /**
     * Lists entries under the given location
     *
     * @return iterable<string> relative entry paths
     */
    public function entries(string $location): iterable;
}
