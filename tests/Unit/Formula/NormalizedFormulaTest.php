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

            |   scalar
            FORMULA;

        self::assertSame(
            'config(a)|default(["x"])|scalar',
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
            'config(a)|scalar',
            (new NormalizedFormula(
                '   config(a)|scalar   ',
            ))->result(),
        );
    }

    #[Test]
    public function preservesJsonLiteral(): void
    {
        self::assertSame(
            'config(a)|default([1])|scalar',
            (new NormalizedFormula(
                'config(a) | default([1]) | scalar',
            ))->result(),
        );
    }
}
