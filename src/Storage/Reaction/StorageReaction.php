<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage\Reaction;

/**
 * Observer notified when files are written to storage
 */
interface StorageReaction
{
    /**
     * Called when a new file is created at the given path
     */
    public function created(string $path): void;

    /**
     * Called when an existing file is overwritten at the given path
     */
    public function updated(string $path): void;
}
