<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Provides access to a nested configuration array
 *
 * Throws PiquleException if value is missing or invalid
 */
final readonly class NestedConfig implements Config
{
    /**
     * @param array<string, mixed> $origin
     */
    public function __construct(private array $origin) {}

    #[Override]
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

        return $value;
    }

    private function traverse(string $name): mixed
    {
        $current = $this->origin;

        foreach (explode('.', $name) as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return [];
            }

            $current = $current[$part];
        }

        return $current;
    }
}
