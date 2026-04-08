<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\PiquleException;
use UnexpectedValueException;

/**
 * A collection of files that exposes an iterable via all()
 */
interface Files
{
    /**
     * Returns all files in this collection.
     *
     * @throws PiquleException
     * @throws UnexpectedValueException
     * @return iterable<File>
     */
    public function all(): iterable;
}
