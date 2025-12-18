<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

interface File
{
    public function hash(): string;

    public function contents(): string;
}
