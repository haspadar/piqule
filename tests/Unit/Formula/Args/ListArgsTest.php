<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ListArgsTest extends TestCase
{
    #[Test]
    public function returnsValuesAsList(): void
    {
        self::assertSame(
            ['alpha', 'beta', 'gamma'],
            (new ListArgs(['alpha', 'beta', 'gamma']))->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenConstructedWithEmptyArray(): void
    {
        self::assertSame(
            [],
            (new ListArgs([]))->values(),
        );
    }
}
