<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Source\Source;

interface Target
{
    public function exists(): bool;

    public function id(): string;

    public function file(): File;

    public function source(): Source;

    public function materialize(): void;
}
