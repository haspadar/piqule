<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Haspadar\Piqule\File\File;

/**
 * An iterable collection of files
 */
interface Files
{
    /**
     * @return iterable<File>
     */
    public function all(): iterable;
}
