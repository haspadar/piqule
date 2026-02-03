<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\File\File;
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
    public function write(File $file): self
    {
        return new self(
            [
                ...$this->entries,
                $file->name() => $file->contents(),
            ],
        );
    }

    #[Override]
    public function entries(string $location): iterable
    {
        $keys = array_keys($this->entries);

        if ($location === '') {
            return $keys;
        }

        $prefix = rtrim($location, '/') . '/';
        $entries = [];

        foreach ($keys as $key) {
            if (!str_starts_with($key, $prefix)) {
                continue;
            }

            $rest = substr($key, strlen($prefix));
            if ($rest !== '' && !str_contains($rest, '/')) {
                $entries[] = $key;
            }
        }

        return $entries;
    }
}
