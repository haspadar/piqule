<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File\Reaction;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;

interface FileReaction
{
    public function created(FileCreated $event): void;

    public function updated(FileUpdated $event): void;

    public function skipped(FileSkipped $event): void;

    public function executableAlreadySet(string $name): void;

    public function executableWasSet(string $name): void;
}
