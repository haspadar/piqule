<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ArrayArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ArrayArgsTest extends TestCase
{
    #[Test]
    public function returnsItemsAsList(): void
    {
        self::assertSame(
            ['alpha', 'beta', 'gamma'],
            (new ArrayArgs(['alpha', 'beta', 'gamma']))->list(),
        );
    }

    #[Test]
    public function returnsCommaSeparatedText(): void
    {
        self::assertSame(
            '[one,two]',
            (new ArrayArgs(['one', 'two']))->text(),
        );
    }
}
