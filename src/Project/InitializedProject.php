<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Step\Scenario;

final readonly class InitializedProject implements Project
{
    public function init(Scenario $scenario): void
    {
        throw new PiquleException('Project is already initialized');
    }

    public function update(): void
    {
        throw new PiquleException('Update is not implemented yet');
    }
}
