<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\ParsedArgs;
use Haspadar\Piqule\Formula\Args\TrimmedArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TrimmedArgsTest extends TestCase
{
    #[Test]
    public function trimsSingleStringValue(): void
    {
        $args = new TrimmedArgs(
            new ListArgs(['  abc  ']),
        );

        self::assertSame(
            ['abc'],
            $args->values(),
            'TrimmedArgs must strip surrounding whitespace from string values',
        );
    }

    #[Test]
    public function doesNotModifyNonStringValues(): void
    {
        $args = new TrimmedArgs(
            new ListArgs([1, true, 3.14]),
        );

        self::assertSame(
            [1, true, 3.14],
            $args->values(),
            'TrimmedArgs must leave non-string values unchanged',
        );
    }

    #[Test]
    public function trimsItemsInsideParsedJsonList(): void
    {
        $args = new TrimmedArgs(
            new ParsedArgs(
                new ListArgs(['[" a "," b "]']),
            ),
        );

        self::assertSame(
            ['a', 'b'],
            $args->values(),
            'TrimmedArgs must trim whitespace from each string item in a parsed JSON list',
        );
    }

    #[Test]
    public function trimsMixedParsedJsonList(): void
    {
        $args = new TrimmedArgs(
            new ParsedArgs(
                new ListArgs(['[" x ",42,false]']),
            ),
        );

        self::assertSame(
            ['x', 42, false],
            $args->values(),
            'TrimmedArgs must trim only string items in a mixed-type parsed JSON list',
        );
    }
}
