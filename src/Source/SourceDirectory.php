<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

interface SourceDirectory
{
    /**
     * @return iterable<SourceFile>
     */
    public function files(): iterable;
}
