<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula;

use Haspadar\Piqule\Formula\NormalizedFormula;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NormalizedFormulaTest extends TestCase
{
    #[Test]
    public function collapsesMultilineExpression(): void
    {
        $raw = <<<'FORMULA'
            config(a)

            |   default(["x"])

            |   format("%s")
            FORMULA;

        self::assertSame(
            'config(a)|default(["x"])|format("%s")',
            (new NormalizedFormula($raw))->result(),
        );
    }

    #[Test]
    public function normalizesPipeSpacing(): void
    {
        self::assertSame(
            'config(a)|join(",")',
            (new NormalizedFormula(
                'config(a)   |   join(",")',
            ))->result(),
        );
    }

    #[Test]
    public function trimsOuterWhitespace(): void
    {
        self::assertSame(
            'config(a)|format("%s")',
            (new NormalizedFormula(
                '   config(a)|format("%s")   ',
            ))->result(),
        );
    }

    #[Test]
    public function preservesJsonLiteral(): void
    {
        self::assertSame(
            'config(a)|default([1])|format("%s")',
            (new NormalizedFormula(
                'config(a) | default([1]) | format("%s")',
            ))->result(),
        );
    }
}
