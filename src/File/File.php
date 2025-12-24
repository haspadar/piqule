<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\PiquleException;

interface File
{
    /**
     * Returns the full contents of the file.
     *
     * @throws PiquleException If the contents cannot be read
     */
    public function contents(): string;
}
