<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

interface File
{
    public function name(): string;

    public function read(): string;

    public function write(string $contents): self;
}
