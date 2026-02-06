<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Replacement;

use Haspadar\Piqule\Replacement\ListReplacement;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ListReplacementTest extends TestCase
{
    #[Test]
    public function returnsFormattedAndJoinedValues(): void
    {
        self::assertSame(
            "  - a\n  - b",
            (new ListReplacement(
                ['a', 'b'],
                '  - %s',
                "\n",
            ))->value(),
            'ListReplacement did not format and join values correctly',
        );
    }
}
