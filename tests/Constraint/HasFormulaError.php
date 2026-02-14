<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Constraint;

use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Constraint\Constraint;

final class HasFormulaError extends Constraint
{
    public function __construct(
        private readonly string $fileName,
        private readonly string $formulaPart,
        private readonly string $reasonPart,
    ) {}

    protected function matches($other): bool
    {
        try {
            $other->contents();

            return false;
        } catch (PiquleException $e) {
            $message = $e->getMessage();

            return str_contains($message, $this->fileName)
                && str_contains($message, $this->formulaPart)
                && str_contains($message, $this->reasonPart);
        }
    }

    public function toString(): string
    {
        return 'has formula error with file context';
    }
}
