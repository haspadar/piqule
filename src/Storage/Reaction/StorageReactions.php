<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage\Reaction;

use Override;

/**
 * Composite StorageReaction that broadcasts events to a list of reactions
 */
final readonly class StorageReactions implements StorageReaction
{
    /** @param list<StorageReaction> $reactions */
    public function __construct(
        private array $reactions,
    ) {}

    #[Override]
    public function created(string $path): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->created($path);
        }
    }

    #[Override]
    public function updated(string $path): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->updated($path);
        }
    }
}
