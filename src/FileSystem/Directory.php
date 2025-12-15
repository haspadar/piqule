<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

interface Directory
{
    public function exists(): bool;

    /**
     * @return iterable<File>
     */
    public function files(): iterable;
}
