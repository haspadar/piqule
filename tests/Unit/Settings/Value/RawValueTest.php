<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings\Value;

use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\FloatValue;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\ListValue;
use Haspadar\Piqule\Settings\Value\RawValue;
use Haspadar\Piqule\Settings\Value\StringValue;
use Haspadar\Piqule\Settings\Value\TreeValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

final class RawValueTest extends TestCase
{
    #[Test]
    public function wrapsBooleanInBoolValue(): void
    {
        self::assertEquals(
            new BoolValue(true),
            (new RawValue(true))->value(),
            'RawValue must wrap a boolean payload in BoolValue',
        );
    }

    #[Test]
    public function wrapsIntegerInIntValue(): void
    {
        self::assertEquals(
            new IntValue(8),
            (new RawValue(8))->value(),
            'RawValue must wrap an integer payload in IntValue',
        );
    }

    #[Test]
    public function wrapsFloatInFloatValue(): void
    {
        self::assertEquals(
            new FloatValue(0.5),
            (new RawValue(0.5))->value(),
            'RawValue must wrap a float payload in FloatValue',
        );
    }

    #[Test]
    public function wrapsStringInStringValue(): void
    {
        self::assertEquals(
            new StringValue('1G'),
            (new RawValue('1G'))->value(),
            'RawValue must wrap a string payload in StringValue',
        );
    }

    #[Test]
    public function wrapsListInListValueWithRecursivelyWrappedItems(): void
    {
        self::assertEquals(
            new ListValue([new StringValue('src'), new IntValue(2)]),
            (new RawValue(['src', 2]))->value(),
            'RawValue must wrap a list payload in ListValue and recurse over items',
        );
    }

    #[Test]
    public function wrapsAssociativeArrayInTreeValue(): void
    {
        self::assertEquals(
            new TreeValue(['ignoreAbstract' => new BoolValue(true)]),
            (new RawValue(['ignoreAbstract' => true]))->value(),
            'RawValue must wrap an associative array in TreeValue',
        );
    }

    #[Test]
    public function recursesIntoNestedTreeStructures(): void
    {
        self::assertEquals(
            new TreeValue([
                'haspadar' => new TreeValue([
                    'afferentCoupling' => new TreeValue([
                        'ignoreAbstract' => new BoolValue(true),
                    ]),
                ]),
            ]),
            (new RawValue([
                'haspadar' => [
                    'afferentCoupling' => [
                        'ignoreAbstract' => true,
                    ],
                ],
            ]))->value(),
            'RawValue must recurse through nested associative arrays',
        );
    }

    #[Test]
    public function throwsOnUnsupportedPayloadType(): void
    {
        $this->expectException(TypeError::class);

        (new RawValue(new stdClass()))->value();
    }
}
