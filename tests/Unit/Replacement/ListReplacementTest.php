<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Replacement;

use Haspadar\Piqule\Replacement\ListReplacement;
use Haspadar\Piqule\Replacement\ScalarReplacement;
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

    #[Test]
    public function ignoresDefaultReplacement(): void
    {
        self::assertSame(
            '<x>1</x>',
            (new ListReplacement(
                ['1'],
                '<x>%s</x>',
                '',
            ))->withDefault(
                new ScalarReplacement('fallback'),
            )->value(),
            'ListReplacement should ignore provided default',
        );
    }
}
