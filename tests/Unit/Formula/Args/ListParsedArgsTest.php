<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListParsedArgs;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsList;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ListParsedArgsTest extends TestCase
{
    #[Test]
    public function returnsItemsWhenPhpListGiven(): void
    {
        self::assertThat(
            new ListParsedArgs(
                new RawArgs('[a,b,c]'),
            ),
            new HasArgsList(['a', 'b', 'c']),
            'ListParsedArgs must split php list',
        );
    }

    #[Test]
    public function throwsWhenNonListGiven(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ListParsedArgs(
            new RawArgs('abc'),
        ))->list();
    }
}
