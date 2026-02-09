<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class NestedConfig implements Config
{
    /**
     * @param array<string, mixed> $origin
     */
    public function __construct(private array $origin) {}

    #[Override]
    public function value(string $name): ConfigValue
    {
        $current = $this->origin;

        foreach (explode('.', $name) as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return new ConfigMissingValue();
            }

            /**
             * @psalm-suppress MixedAssignment
             * Traversal intentionally changes value type (array > mixed)
             */
            $current = $current[$part];
        }

        if (is_scalar($current)) {
            return new ConfigScalarValue($current);
        }

        if (!is_array($current)) {
            throw new PiquleException(
                sprintf(
                    'Config value "%s" must be a scalar or list',
                    $name,
                ),
            );
        }

        if (!array_is_list($current)) {
            throw new PiquleException(
                sprintf(
                    'Config value "%s" must be a list',
                    $name,
                ),
            );
        }

        /** @var list<bool|int|float|string> $current */
        return new ConfigListValue($current);
    }
}
