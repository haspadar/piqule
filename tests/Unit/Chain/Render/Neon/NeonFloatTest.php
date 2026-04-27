<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Chain\Render\Neon;

use Haspadar\Piqule\Chain\Render\Neon\NeonFloat;
use Haspadar\Piqule\Settings\Value\FloatValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NeonFloatTest extends TestCase
{
    #[Test]
    public function rendersFloatAsBareLiteral(): void
    {
        self::assertSame(
            '0.5',
            (new NeonFloat(new FloatValue(0.5)))->rendered(),
            'NeonFloat must render the float payload as a bare neon literal',
        );
    }
}
