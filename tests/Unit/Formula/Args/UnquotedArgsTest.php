<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\ParsedArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UnquotedArgsTest extends TestCase
{
    #[Test]
    public function removesWrappingQuotesFromSingleValue(): void
    {
        $args = new UnquotedArgs(
            new ListArgs(['"a\'b"']),
        );

        self::assertSame(
            ["a'b"],
            $args->values(),
        );
    }

    #[Test]
    public function removesWrappingQuotesFromParsedListItems(): void
    {
        $args = new UnquotedArgs(
            new ParsedArgs(
                new ListArgs(['["a\'b","c"]']),
            ),
        );

        self::assertSame(
            ["a'b", 'c'],
            $args->values(),
        );
    }

    #[Test]
    public function leavesUnquotedValueUnchanged(): void
    {
        $args = new UnquotedArgs(
            new ListArgs(['hello']),
        );

        self::assertSame(
            ['hello'],
            $args->values(),
        );
    }

    #[Test]
    public function returnsEmptyStringForEmptyQuotedString(): void
    {
        $args = new UnquotedArgs(
            new ListArgs(['""']),
        );

        self::assertSame(
            [''],
            $args->values(),
        );
    }

    #[Test]
    public function removesOnlySingleMatchingQuoteLayer(): void
    {
        $args = new UnquotedArgs(
            new ListArgs(['""value""']),
        );

        self::assertSame(
            ['"value"'],
            $args->values(),
        );
    }
}
