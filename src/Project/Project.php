<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Project\Lock\Lock;
use Haspadar\Piqule\Target\Materialization\Materialization;

interface Project
{
    public function init(Materialization $materialization, Lock $lock): void;

    public function update(Materialization $materialization, Lock $lock): void;
}
