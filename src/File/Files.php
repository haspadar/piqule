<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

interface Files
{
    /**
     * @return iterable<File>
     */
    public function all(): iterable;
}
