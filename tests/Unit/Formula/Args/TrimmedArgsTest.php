<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListParsedArgs;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Formula\Args\TrimmedArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsList;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsText;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TrimmedArgsTest extends TestCase
{
    #[Test]
    public function trimsTextWhenWhitespacePresent(): void
    {
        self::assertThat(
            new TrimmedArgs(
                new RawArgs('  abc  '),
            ),
            new HasArgsText('abc'),
            'TrimmedArgs must trim text',
        );
    }

    #[Test]
    public function trimsItemsWhenListContainsWhitespace(): void
    {
        self::assertThat(
            new TrimmedArgs(
                new ListParsedArgs(
                    new RawArgs('[ a , b ]'),
                ),
            ),
            new HasArgsList(['a', 'b']),
            'TrimmedArgs must trim list items',
        );
    }
}
