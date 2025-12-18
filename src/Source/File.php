<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

interface File
{
    public function exists(): bool;

    public function hash(): string;

    public function contents(): string;
}
