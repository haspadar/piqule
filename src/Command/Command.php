<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Command;

interface Command
{
    public function run(): void;
}
