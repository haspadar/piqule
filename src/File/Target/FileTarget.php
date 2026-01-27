<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File\Target;

use Haspadar\Piqule\File\Event\FileCreated;

interface FileTarget
{
    public function created(FileCreated $event): void;
}
