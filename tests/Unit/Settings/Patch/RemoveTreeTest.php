<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings\Patch;

use Haspadar\Piqule\Settings\Patch\RemoveTree;
use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\TreeValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TypeError;

final class RemoveTreeTest extends TestCase
{
    #[Test]
    public function exposesTargetKey(): void
    {
        self::assertSame(
            'phpstan.parameters',
            (new RemoveTree('phpstan.parameters', []))->key(),
            'RemoveTree must expose the configuration key it targets',
        );
    }

    #[Test]
    public function dropsNamedKeyFromBase(): void
    {
        $base = new TreeValue([
            'kept' => new IntValue(1),
            'removed' => new BoolValue(true),
        ]);

        self::assertEquals(
            new TreeValue(['kept' => new IntValue(1)]),
            (new RemoveTree('phpstan.parameters', ['removed']))->applied($base),
            'RemoveTree must drop the entry whose key is listed for removal',
        );
    }

    #[Test]
    public function ignoresKeyAbsentInBase(): void
    {
        $base = new TreeValue(['kept' => new IntValue(1)]);

        self::assertEquals(
            $base,
            (new RemoveTree('phpstan.parameters', ['absent']))->applied($base),
            'RemoveTree must keep base entries when removal targets keys absent from base',
        );
    }

    #[Test]
    public function rejectsBaseValueThatIsNotATree(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('phpstan.parameters');

        (new RemoveTree('phpstan.parameters', []))->applied(new IntValue(8));
    }
}
