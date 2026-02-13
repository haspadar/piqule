<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

interface Config
{
    /**
     * Returns configuration values for a dot-notated path.
     *
     * Missing paths and explicitly empty lists are both represented as an empty list.
     *
     * @return list<int|float|string|bool>
     */
    public function values(string $name): array;
}
