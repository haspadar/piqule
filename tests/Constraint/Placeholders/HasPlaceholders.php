<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Constraint\Placeholders;

use Haspadar\Piqule\Placeholder\Placeholder;
use Haspadar\Piqule\Placeholders\Placeholders;
use PHPUnit\Framework\Constraint\Constraint;

final class HasPlaceholders extends Constraint
{
    /**
     * @param array<string, string> $expected expression => default
     */
    public function __construct(
        private readonly array $expected,
    ) {}

    public function toString(): string
    {
        return 'has placeholders ' . $this->export($this->expected);
    }

    protected function matches($other): bool
    {
        if (!$other instanceof Placeholders) {
            return false;
        }

        $actual = [];

        foreach ($other->all() as $placeholder) {
            if (!$placeholder instanceof Placeholder) {
                return false;
            }

            $actual[$placeholder->expression()] = $placeholder->replacement();
        }

        ksort($actual);
        $expected = $this->expected;
        ksort($expected);

        return $actual === $expected;
    }

    protected function failureDescription($other): string
    {
        return 'placeholders ' . $this->toString();
    }

    protected function additionalFailureDescription($other): string
    {
        if (!$other instanceof Placeholders) {
            return "\nBut object of type "
                . get_debug_type($other)
                . ' was given instead of Placeholders';
        }

        $actual = [];

        foreach ($other->all() as $placeholder) {
            $actual[$placeholder->expression()] = $placeholder->replacement();
        }

        ksort($actual);

        return "\nExpected: {$this->export($this->expected)}"
            . "\nBut was:  {$this->export($actual)}";
    }

    private function export(mixed $value): string
    {
        return var_export($value, true);
    }
}
