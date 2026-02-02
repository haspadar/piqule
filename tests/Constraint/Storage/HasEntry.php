<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Constraint\Storage;

use Haspadar\Piqule\Storage\Storage;
use PHPUnit\Framework\Constraint\Constraint;

final class HasEntry extends Constraint
{
    public function __construct(
        private readonly string $location,
        private readonly string $expected,
    ) {}

    public function toString(): string
    {
        return "has entry {$this->export($this->location)} with contents {$this->export($this->expected)}";
    }

    protected function matches($other): bool
    {
        return $other instanceof Storage
            && $other->exists($this->location)
            && $other->read($this->location) === $this->expected;
    }

    protected function failureDescription($other): string
    {
        return 'storage ' . $this->toString();
    }

    protected function additionalFailureDescription($other): string
    {
        if (!$other instanceof Storage) {
            return "\nBut object of type "
                . get_debug_type($other)
                . ' was given instead of Storage';
        }

        if (!$other->exists($this->location)) {
            return "\nBut no entry exists at location {$this->export($this->location)}";
        }

        return "\nExpected: {$this->export($this->expected)}"
            . "\nBut was:  {$this->export($other->read($this->location))}";
    }

    private function export(mixed $value): string
    {
        return var_export($value, true);
    }
}
