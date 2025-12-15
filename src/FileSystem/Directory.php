<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

interface Directory
{
    public function exists(): bool;

    /**
     * @return list<File>
     */
    public function files(): iterable;
}
