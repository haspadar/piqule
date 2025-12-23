<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Snapshot;

use Haspadar\Piqule\Project\Hashes;

final readonly class Snapshot
{
    public function __construct(private Hashes $hashes) {}

    public function has(string $id): bool
    {
        return $this->hashes->has($id);
    }

    public function hashOf(string $id): string
    {
        return $this->hashes->get($id);
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->hashes->values();
    }

    public function with(string $id, string $hash): Snapshot
    {
        return new self(
            $this->hashes->with($id, $hash),
        );
    }
}
