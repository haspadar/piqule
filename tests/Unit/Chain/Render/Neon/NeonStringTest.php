<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Chain\Render\Neon;

use Haspadar\Piqule\Chain\Render\Neon\NeonString;
use Haspadar\Piqule\Settings\Value\StringValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NeonStringTest extends TestCase
{
    #[Test]
    public function rendersStringAsDoubleQuotedLiteral(): void
    {
        self::assertSame(
            '"1G"',
            (new NeonString(new StringValue('1G')))->rendered(),
            'NeonString must render the string payload wrapped in double quotes',
        );
    }

    #[Test]
    public function escapesEmbeddedDoubleQuote(): void
    {
        self::assertSame(
            '"a\\"b"',
            (new NeonString(new StringValue('a"b')))->rendered(),
            'NeonString must backslash-escape an embedded double quote',
        );
    }

    #[Test]
    public function escapesEmbeddedBackslash(): void
    {
        self::assertSame(
            '"a\\\\b"',
            (new NeonString(new StringValue('a\\b')))->rendered(),
            'NeonString must backslash-escape an embedded backslash',
        );
    }
}
