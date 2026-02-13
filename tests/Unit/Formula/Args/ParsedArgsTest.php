<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\ParsedArgs;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ParsedArgsTest extends TestCase
{
    #[Test]
    public function parsesPhpListLiteralIntoValues(): void
    {
        self::assertSame(
            ['one', 'two', 'three'],
            (new ParsedArgs(
                new ListArgs(['["one","two","three"]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesPhpListLiteralWithNumbers(): void
    {
        self::assertSame(
            [1, 2, 3],
            (new ParsedArgs(
                new ListArgs(['[1,2,3]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesPhpListLiteralWithBooleans(): void
    {
        self::assertSame(
            [true, false],
            (new ParsedArgs(
                new ListArgs(['[true,false]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesPhpListLiteralWithMixedScalars(): void
    {
        self::assertSame(
            ['x', 42, false],
            (new ParsedArgs(
                new ListArgs(['["x",42,false]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesPhpListLiteralWithCommaInsideQuotedString(): void
    {
        self::assertSame(
            ['a,b', 'c'],
            (new ParsedArgs(
                new ListArgs(['["a,b","c"]']),
            ))->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenLiteralIsEmpty(): void
    {
        self::assertSame(
            [],
            (new ParsedArgs(
                new ListArgs(['[]']),
            ))->values(),
        );
    }

    #[Test]
    public function throwsWhenInputIsNotPhpListLiteral(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['alpha,beta']),
        ))->values();
    }

    #[Test]
    public function throwsWhenOpeningBracketMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['foo,bar]']),
        ))->values();
    }

    #[Test]
    public function throwsWhenClosingBracketMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['[foo,bar']),
        ))->values();
    }

    #[Test]
    public function throwsWhenInputIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs([]),
        ))->values();
    }

    #[Test]
    public function throwsWhenFirstElementIsNotString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs([123]),
        ))->values();
    }

    #[Test]
    public function throwsWhenLiteralIsEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['']),
        ))->values();
    }

    #[Test]
    public function throwsWhenLiteralContainsNonScalar(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['[new stdClass()]']),
        ))->values();
    }
}
