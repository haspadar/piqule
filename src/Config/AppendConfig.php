<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

/**
 * @phpstan-type AppendMap array<string, scalar|list<scalar>>
 */
final readonly class AppendConfig implements Config
{
    /**
     * @param AppendMap $appends
     */
    public function __construct(
        private Config $origin,
        private array $appends,
    ) {}

    #[Override]
    public function has(string $name): bool
    {
        return $this->origin->has($name);
    }

    #[Override]
    public function list(string $name): array
    {
        if (!$this->origin->has($name)) {
            throw new PiquleException(
                sprintf('Unknown config key "%s"', $name),
            );
        }

        if (!array_key_exists($name, $this->appends)) {
            return $this->origin->list($name);
        }

        $extra = $this->normalizedValue($this->appends[$name], $name);

        return array_values(array_unique(array_merge($this->origin->list($name), $extra)));
    }

    /**
     * @return list<scalar>
     */
    private function normalizedValue(mixed $value, string $name): array
    {
        if (is_scalar($value)) {
            return [$value];
        }

        if (!is_array($value) || !array_is_list($value)) {
            throw new PiquleException(
                sprintf('Append "%s" must be scalar or list<scalar>', $name),
            );
        }

        foreach ($value as $item) {
            if (!is_scalar($item)) {
                throw new PiquleException(
                    sprintf('Append "%s" must contain only scalars', $name),
                );
            }
        }

        /** @var list<scalar> $value */
        return $value;
    }
}
