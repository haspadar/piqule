<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Artifact;

use Haspadar\Piqule\PiquleException;

interface File
{
    /**
     * Returns a stable identifier of the file source
     *
     * The identifier must uniquely represent the origin of the file
     * within the current execution context
     */
    public function id(): string;

    /**
     * Returns the full contents of the file
     *
     * @throws PiquleException If the contents cannot be read
     */
    public function contents(): string;
}
