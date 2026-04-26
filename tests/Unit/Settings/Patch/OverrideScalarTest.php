<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings\Patch;

use Haspadar\Piqule\Settings\Patch\OverrideScalar;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\StringValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OverrideScalarTest extends TestCase
{
    #[Test]
    public function exposesTargetKey(): void
    {
        self::assertSame(
            'phpstan.level',
            (new OverrideScalar('phpstan.level', new IntValue(8)))->key(),
            'OverrideScalar must expose the configuration key it targets',
        );
    }

    #[Test]
    public function replacesBaseWithReplacementValue(): void
    {
        self::assertEquals(
            new IntValue(8),
            (new OverrideScalar('phpstan.level', new IntValue(8)))->applied(new IntValue(9)),
            'OverrideScalar must replace the base value with the replacement scalar',
        );
    }

    #[Test]
    public function ignoresBaseScalarTypeWhenReplacing(): void
    {
        self::assertEquals(
            new IntValue(8),
            (new OverrideScalar('phpstan.level', new IntValue(8)))->applied(new StringValue('old')),
            'OverrideScalar must ignore the base value entirely, even when its scalar type differs',
        );
    }
}
