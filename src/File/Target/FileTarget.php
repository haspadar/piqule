<?php
declare(strict_types=1);

namespace Haspadar\Piqule\File\Target;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;

interface FileTarget
{
    public function created(FileCreated $event): void;

    public function updated(FileUpdated $event): void;

    public function skipped(FileSkipped $event): void;
}
