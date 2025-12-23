<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Target\Materialization\Materialization;

interface Project
{
    public function sync(Materialization $materialization): void;
}
