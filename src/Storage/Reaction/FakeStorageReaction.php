<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage\Reaction;

use Override;

final class FakeStorageReaction implements StorageReaction
{
    /** @var list<string> */
    private array $created = [];

    /** @var list<string> */
    private array $updated = [];

    #[Override]
    public function created(string $path): void
    {
        $this->created[] = $path;
    }

    #[Override]
    public function updated(string $path): void
    {
        $this->updated[] = $path;
    }

    /** @return list<string> */
    public function createdPaths(): array
    {
        return $this->created;
    }

    /** @return list<string> */
    public function updatedPaths(): array
    {
        return $this->updated;
    }
}
