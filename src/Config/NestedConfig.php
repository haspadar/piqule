<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Provides access to a nested configuration array
 *
 * Returns an empty list if the path is missing
 * Throws PiquleException if the resolved value is not a scalar
 * or a sequential list of scalars
 */
final readonly class NestedConfig implements Config
{
    public function __construct(private array $origin) {}

    #[Override]
    /**
     * @return list<int|float|string|bool>
     */
    public function values(string $name): array
    {
        $value = $this->traverse($name);

        if (is_scalar($value)) {
            return [$value];
        }

        if (!is_array($value)) {
            throw new PiquleException(
                sprintf(
                    'Config value "%s" must be scalar or list, got %s',
                    $name,
                    get_debug_type($value),
                ),
            );
        }

        if (!array_is_list($value)) {
            throw new PiquleException(
                sprintf(
                    'Config list "%s" must be sequential array',
                    $name,
                ),
            );
        }

        foreach ($value as $item) {
            if (!is_scalar($item)) {
                throw new PiquleException(
                    sprintf(
                        'Config list "%s" must contain only scalars, got %s',
                        $name,
                        get_debug_type($item),
                    ),
                );
            }
        }

        /** @var list<int|float|string|bool> $value */
        return $value;
    }

    private function traverse(string $name): mixed
    {
        /** @var array<string, mixed> $current */
        $current = $this->origin;
        foreach (explode('.', $name) as $part) {
            if (!array_key_exists($part, $current)) {
                return [];
            }

            $next = $current[$part];
            if (!is_array($next)) {
                return $next;
            }

            /** @var array<string, mixed> $current */
            $current = $next;
        }

        return $current;
    }
}
