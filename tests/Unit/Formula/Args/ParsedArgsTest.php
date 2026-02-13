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
                new ListArgs(['[one,two,three]']),
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
}
