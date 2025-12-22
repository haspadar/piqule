<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

final readonly class Hashes
{
    /**
     * @param array<string,string> $values
     */
    public function __construct(
        private array $values,
    ) {}

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public function get(string $key): string
    {
        return $this->values[$key];
    }

    public function with(string $key, string $hash): self
    {
        return new self(
            array_replace($this->values, [$key => $hash]),
        );
    }

    public function values(): array
    {
        return $this->values;
    }
}
