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
    public function parsesJsonListLiteralIntoValues(): void
    {
        self::assertSame(
            ['one', 'two', 'three'],
            (new ParsedArgs(
                new ListArgs(['["one","two","three"]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesJsonListWithNumbers(): void
    {
        self::assertSame(
            [1, 2, 3],
            (new ParsedArgs(
                new ListArgs(['[1,2,3]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesJsonListWithBooleans(): void
    {
        self::assertSame(
            [true, false],
            (new ParsedArgs(
                new ListArgs(['[true,false]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesJsonListWithMixedScalars(): void
    {
        self::assertSame(
            ['x', 42, false],
            (new ParsedArgs(
                new ListArgs(['["x",42,false]']),
            ))->values(),
        );
    }

    #[Test]
    public function parsesJsonListWithCommaInsideQuotedString(): void
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
    public function throwsWhenInputIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs([]),
        ))->values();
    }

    #[Test]
    public function throwsWhenMoreThanOneLiteralProvided(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['[1,2]', '[3,4]']),
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
    public function throwsWhenInvalidJson(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['alpha,beta']),
        ))->values();
    }

    #[Test]
    public function throwsWhenUsingSingleQuotes(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(["['a','b']"]),
        ))->values();
    }

    #[Test]
    public function throwsWhenTrailingCommaPresent(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['["a","b",]']),
        ))->values();
    }

    #[Test]
    public function throwsWhenLiteralIsNotList(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['{"a":1}']),
        ))->values();
    }

    #[Test]
    public function throwsWhenListContainsNonScalar(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['[{"a":1}]']),
        ))->values();
    }

    #[Test]
    public function throwsWhenLiteralIsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ParsedArgs(
            new ListArgs(['null']),
        ))->values();
    }
}
