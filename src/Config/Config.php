<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

interface Config
{
    public function value(string $name): ConfigValue;
}
