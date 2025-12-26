<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Output\Color;

use Haspadar\Piqule\Output\Color\Red;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RedTest extends TestCase
{
    #[Test]
    public function addsAnsiRedPrefixWhenApplied(): void
    {
        self::assertStringContainsString(
            "\033[31m",
            (new Red())->apply('error'),
            'Red color must add ANSI red prefix',
        );
    }

    #[Test]
    public function addsAnsiResetSuffixWhenApplied(): void
    {
        self::assertStringContainsString(
            "\033[0m",
            (new Red())->apply('error'),
            'Red color must add ANSI reset suffix',
        );
    }
}
