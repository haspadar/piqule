<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Chain\Render\Neon;

use Haspadar\Piqule\Chain\Render\Neon\NeonTree;
use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\TreeValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NeonTreeTest extends TestCase
{
    #[Test]
    public function rendersEmptyTreeAsEmptyMapping(): void
    {
        self::assertSame(
            '{}',
            (new NeonTree(new TreeValue([])))->rendered(),
            'NeonTree must render an empty tree as the neon empty-flow mapping',
        );
    }

    #[Test]
    public function rendersFlatEntriesAsBlockMapping(): void
    {
        self::assertSame(
            "\n    level: 9",
            (new NeonTree(new TreeValue(['level' => new IntValue(9)])))->rendered(),
            'NeonTree must render a single scalar entry as an indented block mapping line',
        );
    }

    #[Test]
    public function indentsNestedTreesOneLevelDeeper(): void
    {
        self::assertSame(
            "\n    haspadar:\n        ignoreAbstract: true",
            (new NeonTree(new TreeValue([
                'haspadar' => new TreeValue([
                    'ignoreAbstract' => new BoolValue(true),
                ]),
            ])))->rendered(),
            'NeonTree must indent nested trees one level deeper than the parent',
        );
    }
}
