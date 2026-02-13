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
        );
    }
}
