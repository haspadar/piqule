<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Appends values to existing configuration lists without replacing them
 *
 * Example:
 *
 *     new AppendConfig(new DefaultConfig(), [
 *         'phpstan.neon_includes' => ['../../rules.neon'],
 *         'exclude' => ['legacy'],
 *     ]);
 */
final readonly class AppendConfig implements Config
{
    /** @param array<string, mixed> $appends */
    public function __construct(private Config $defaults, private array $appends) {}

    #[Override]
    public function has(string $name): bool
    {
        return $this->defaults->has($name);
    }

    /**
     * Returns the default list with appended values merged in
     *
     * @throws PiquleException
     * @return list<scalar>
     */
    #[Override]
    public function list(string $name): array
    {
        if (!$this->defaults->has($name)) {
            throw new PiquleException(
                sprintf('Unknown config key "%s"', $name),
            );
        }

        if (!array_key_exists($name, $this->appends)) {
            return $this->defaults->list($name);
        }

        $appends = $this->appends[$name];

        if (!is_array($appends) || !array_is_list($appends)) {
            throw new PiquleException(
                sprintf('Append "%s" must be a list<scalar>', $name),
            );
        }

        $scalars = [];

        foreach ($appends as $item) {
            if (!is_scalar($item)) {
                throw new PiquleException(
                    sprintf('Append "%s" must contain only scalars', $name),
                );
            }

            $scalars[] = $item;
        }

        return [...$this->defaults->list($name), ...$scalars];
    }

    /** @throws PiquleException */
    #[Override]
    public function toArray(): array
    {
        $result = $this->defaults->toArray();

        foreach (array_keys($result) as $key) {
            $result[$key] = $this->list($key);
        }

        return $result;
    }
}
