<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;

/**
 * Read-only access to flat dot-notated configuration keys
 */
interface Config
{
    /**
     * Returns true if the key is declared in this configuration
     */
    public function has(string $name): bool;

    /**
     * Returns configuration values for a dot-notated path
     *
     * Missing paths and explicitly empty lists are both represented as an empty list
     *
     * @throws PiquleException
     * @return list<scalar>
     */
    public function list(string $name): array;
}
