<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Chain\Plain;

use Haspadar\Piqule\Chain\Plain\BoolText;
use Haspadar\Piqule\Settings\Value\BoolValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BoolTextTest extends TestCase
{
    #[Test]
    public function rendersTrueAsLiteralTrue(): void
    {
        self::assertSame(
            'true',
            (new BoolText(new BoolValue(true)))->rendered(),
            'BoolText must render true as the plain literal "true"',
        );
    }

    #[Test]
    public function rendersFalseAsLiteralFalse(): void
    {
        self::assertSame(
            'false',
            (new BoolText(new BoolValue(false)))->rendered(),
            'BoolText must render false as the plain literal "false"',
        );
    }
}
