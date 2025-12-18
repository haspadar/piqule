<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;

final readonly class UninitializedProject implements Project
{
    public function init(): void
    {

    }

    public function update(): void
    {
        throw new PiquleException('Project is not initialized');
    }
}
