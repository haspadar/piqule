<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class InMemoryStorage implements Storage
{
    /** @var array<string, string> */
    private array $entries;

    /**
     * @param array<string, string> $entries
     */
    public function __construct(array $entries = [])
    {
        $this->entries = $entries;
    }

    #[Override]
    public function read(string $location): string
    {
        if (!array_key_exists($location, $this->entries)) {
            throw new PiquleException("Location not found: {$location}");
        }

        return $this->entries[$location];
    }

    #[Override]
    public function exists(string $location): bool
    {
        return array_key_exists($location, $this->entries);
    }

    #[Override]
    public function write(string $location, string $contents): self
    {
        return new self(
            [...$this->entries, $location => $contents],
        );
    }

    #[Override]
    public function entries(string $location): iterable
    {
        return array_keys($this->entries);
    }
}
