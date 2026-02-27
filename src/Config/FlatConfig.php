<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class FlatConfig implements Config
{
    /**
     * @param array<string, mixed> $origin
     */
    public function __construct(private array $origin) {}

    #[Override]
    /**
     * @return list<int|float|string|bool>
     */
    public function values(string $name): array
    {
        if (!array_key_exists($name, $this->origin)) {
            return [];
        }

        $value = $this->origin[$name];

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
}
