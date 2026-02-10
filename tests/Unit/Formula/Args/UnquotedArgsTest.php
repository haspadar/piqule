<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListParsedArgs;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsList;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsText;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UnquotedArgsTest extends TestCase
{
    #[Test]
    public function removesWrappingQuotesWhenTextIsQuoted(): void
    {
        self::assertThat(
            new UnquotedArgs(
                new RawArgs('"a\'b"'),
            ),
            new HasArgsText("a'b"),
            'UnquotedArgs must remove wrapping quotes from text',
        );
    }

    #[Test]
    public function removesWrappingQuotesWhenListItemsQuoted(): void
    {
        self::assertThat(
            new UnquotedArgs(
                new ListParsedArgs(
                    new RawArgs('["a\'b","c"]'),
                ),
            ),
            new HasArgsList(["a'b", 'c']),
            'UnquotedArgs must remove wrapping quotes from list items',
        );
    }
}
