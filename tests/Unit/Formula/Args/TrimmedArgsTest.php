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
    public function trimsItemsInsideParsedList(): void
    {
        $args = new TrimmedArgs(
            new ParsedArgs(
                new ListArgs(['[ a , b ]']),
            ),
        );

        self::assertSame(
            ['a', 'b'],
            $args->values(),
        );
    }
}
