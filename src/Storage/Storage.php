<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

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
     * Writes contents to the given location
     *
     * Creates or overwrites the projection
     */
    public function write(string $location, string $contents): self;

    /**
     * Checks whether a projection exists at the given location
     */
    public function exists(string $location): bool;
}
