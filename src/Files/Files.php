<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Haspadar\Piqule\File\File;

/**
 * A collection of files that exposes an iterable via all()
 */
interface Files
{
    /**
     * @return iterable<File>
     */
    public function all(): iterable;
}
