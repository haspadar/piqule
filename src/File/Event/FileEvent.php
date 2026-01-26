<?php
declare(strict_types=1);

namespace Haspadar\Piqule\File\Event;

use Haspadar\Piqule\File\Target\FileTarget;

interface FileEvent
{
    /**
     * Logical file name
     */
    public function name(): string;

    /**
     * Pass this event to a target
     */
    public function passTo(FileTarget $target): void;
}
