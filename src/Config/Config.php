<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

interface Config
{
    /**
     * @return list<int|float|string|bool>
     */
    public function values(string $name): array;
}
