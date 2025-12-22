<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

interface PiquleDirectory
{
    public function exists(): bool;

    public function path(): string;
}
