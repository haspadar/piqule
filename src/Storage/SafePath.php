<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\PiquleException;

/**
 * Resolves a relative location to an absolute path within a storage root, preventing path traversal.
 */
final readonly class SafePath
{
    /** Initializes with the storage root directory. */
    public function __construct(private string $root) {}

    /**
     * Returns the safe absolute path for a relative location.
     *
     * @throws PiquleException
     */
    public function resolve(string $location): string
    {
        $parts = [];

        foreach (explode('/', str_replace('\\', '/', $location)) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }

            if ($part === '..') {
                if ($parts === []) {
                    throw new PiquleException("Invalid location: $location");
                }

                array_pop($parts);

                continue;
            }

            $parts[] = $part;
        }

        return rtrim($this->root, '/') . '/' . implode('/', $parts);
    }
}
