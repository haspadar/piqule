<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings\Value;

use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\MergedTree;
use Haspadar\Piqule\Settings\Value\TreeValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MergedTreeTest extends TestCase
{
    #[Test]
    public function copiesBaseEntriesWhenOverrideIsEmpty(): void
    {
        $base = new TreeValue(['a' => new IntValue(1)]);

        self::assertEquals(
            $base,
            (new MergedTree($base, new TreeValue([])))->value(),
            'MergedTree must return the base tree when the override is empty',
        );
    }

    #[Test]
    public function recursesIntoNestedTreesAtSharedKeys(): void
    {
        $base = new TreeValue([
            'outer' => new TreeValue([
                'kept' => new IntValue(1),
                'replaced' => new BoolValue(false),
            ]),
        ]);
        $override = new TreeValue([
            'outer' => new TreeValue(['replaced' => new BoolValue(true)]),
        ]);

        self::assertEquals(
            new TreeValue([
                'outer' => new TreeValue([
                    'kept' => new IntValue(1),
                    'replaced' => new BoolValue(true),
                ]),
            ]),
            (new MergedTree($base, $override))->value(),
            'MergedTree must recurse into nested trees at shared keys and preserve siblings',
        );
    }
}
