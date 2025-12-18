<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

/**
 * Represents the presence of Piqule in a filesystem location
 *
 * This object acts as a sentinel: it does not decide what to do,
 * it only witnesses whether Piqule has been initialized here
 */
final class PiquleSentinel
{
    public function __construct(private string $root) {}

    /**
     * Returns true if Piqule is present in the given root.
     */
    public function exists(): bool
    {
        return is_dir($this->location());
    }

    /**
     * Returns the path to the sentinel directory.
     *
     * @throws PiquleException if the sentinel is not present
     */
    public function path(): string
    {
        $path = $this->location();

        if (!is_dir($path)) {
            throw new PiquleException(
                'Piqule is not initialized in this directory',
            );
        }

        return $path;
    }

    private function location(): string
    {
        return $this->root . '/.piqule';
    }
}
