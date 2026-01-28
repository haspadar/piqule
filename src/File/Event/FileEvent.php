<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File\Event;

use Haspadar\Piqule\File\Reaction\FileReaction;

interface FileEvent
{
    /**
     * Logical file name
     */
    public function name(): string;

    /**
     * Pass this event to a reaction
     */
    public function passTo(FileReaction $reaction): void;
}
