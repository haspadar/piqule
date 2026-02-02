<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Constraint\File;

use Haspadar\Piqule\File\File;
use PHPUnit\Framework\Constraint\Constraint;

final class HasContents extends Constraint
{
    public function __construct(private readonly string $expected) {}

    public function toString(): string
    {
        return "has contents {$this->export($this->expected)}";
    }

    protected function matches($other): bool
    {
        return $other instanceof File
            && $other->read() === $this->expected;
    }

    protected function failureDescription($other): string
    {
        return 'file ' . $this->toString();
    }

    protected function additionalFailureDescription($other): string
    {
        if (!$other instanceof File) {
            return "\nBut object of type "
                . get_debug_type($other)
                . ' was given instead of File';
        }

        return "\nExpected: {$this->export($this->expected)}"
            . "\nBut was:  {$this->export($other->read())}";
    }

    private function export(mixed $value): string
    {
        return var_export($value, true);
    }
}
