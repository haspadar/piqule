<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;

interface PiquleDirectory
{
    public function exists(): bool;

    /**
     * @throws PiquleException if not initialized
     */
    public function path(): string;

    public function lockFile(): string;
}
