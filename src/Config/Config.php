<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

interface Config
{
    public function has(string $name): bool;

    /**
     * Returns configuration values for a dot-notated path
     *
     * Missing paths and explicitly empty lists are both represented as an empty list
     *
     * @return list<scalar>
     */
    public function list(string $name): array;
}
