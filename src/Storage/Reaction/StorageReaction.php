<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage\Reaction;

interface StorageReaction
{
    public function created(string $path): void;

    public function updated(string $path): void;
}
