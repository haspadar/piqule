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

    #[Test]
    public function returnsSameTextWhenInputIsNotQuoted(): void
    {
        self::assertSame(
            'hello',
            (new UnquotedArgs(
                new RawArgs('hello'),
            ))->text(),
            'UnquotedArgs must return original text when no quotes are present',
        );
    }

    #[Test]
    public function returnsEmptyStringWhenEmptyQuotedStringGiven(): void
    {
        self::assertSame(
            '',
            (new UnquotedArgs(
                new RawArgs('""'),
            ))->text(),
            'UnquotedArgs must return empty string for empty quoted input',
        );
    }
}
