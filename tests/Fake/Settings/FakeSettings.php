<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Settings;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Settings\Settings;
use Haspadar\Piqule\Settings\Value\Value;

final readonly class FakeSettings implements Settings
{
    /**
     * @param array<string, Value> $values
     */
    public function __construct(private array $values) {}

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->values);
    }

    public function value(string $name): Value
    {
        if (!$this->has($name)) {
            throw new PiquleException(sprintf('Unknown config key "%s"', $name));
        }

        return $this->values[$name];
    }
}
