<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

interface Project
{
    public function init(): void;

    public function update(): void;
}
