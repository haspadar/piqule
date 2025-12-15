<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

interface SourceDirectory
{
    /**
     * @return iterable<SourceFile>
     */
    public function files(): iterable;
}
