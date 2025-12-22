<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Project\Registry\Registry;
use Haspadar\Piqule\Target\Materialization\Materialization;

interface Project
{
    public function init(Materialization $materialization, Registry $lock): void;

    public function update(Materialization $materialization, Registry $lock): void;
}
