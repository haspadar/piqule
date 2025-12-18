<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Step\Scenario;

interface Project
{
    public function init(Scenario $scenario): void;

    public function update(): void;
}
