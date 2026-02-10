<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Constraint\Formula\Args;

use Haspadar\Piqule\Formula\Args\Args;
use PHPUnit\Framework\Constraint\Constraint;

final class HasArgsText extends Constraint
{
    public function __construct(
        private readonly string $expected,
    ) {}

    public function toString(): string
    {
        return 'has args text ' . $this->export($this->expected);
    }

    protected function matches($other): bool
    {
        if (!$other instanceof Args) {
            return false;
        }

        return $other->text() === $this->expected;
    }

    protected function failureDescription($other): string
    {
        return 'args ' . $this->toString();
    }

    protected function additionalFailureDescription($other): string
    {
        if (!$other instanceof Args) {
            return "\nBut object of type "
                . get_debug_type($other)
                . ' was given instead of Args';
        }

        return "\nExpected: {$this->export($this->expected)}"
            . "\nBut was:  {$this->export($other->text())}";
    }

    private function export(mixed $value): string
    {
        return var_export($value, true);
    }
}
