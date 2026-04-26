<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings\Patch;

use Haspadar\Piqule\Settings\Patch\RemoveList;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\ListValue;
use Haspadar\Piqule\Settings\Value\StringValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TypeError;

final class RemoveListTest extends TestCase
{
    #[Test]
    public function exposesTargetKey(): void
    {
        self::assertSame(
            'phpstan.checked_exceptions',
            (new RemoveList('phpstan.checked_exceptions', new ListValue([])))->key(),
            'RemoveList must expose the configuration key it targets',
        );
    }

    #[Test]
    public function dropsMatchingEntriesFromBase(): void
    {
        $base = new ListValue([
            new StringValue('\\Throwable'),
            new StringValue('\\RuntimeException'),
        ]);
        $items = new ListValue([new StringValue('\\Throwable')]);

        self::assertEquals(
            new ListValue([new StringValue('\\RuntimeException')]),
            (new RemoveList('phpstan.checked_exceptions', $items))->applied($base),
            'RemoveList must drop entries from the base list when they match items',
        );
    }

    #[Test]
    public function keepsBaseEntriesAbsentFromItems(): void
    {
        $base = new ListValue([new StringValue('vendor'), new StringValue('tests')]);
        $items = new ListValue([new StringValue('dist')]);

        self::assertEquals(
            $base,
            (new RemoveList('infra.exclude', $items))->applied($base),
            'RemoveList must keep base entries when items list does not contain them',
        );
    }

    #[Test]
    public function reindexesRemainingEntries(): void
    {
        $base = new ListValue([
            new StringValue('a'),
            new StringValue('b'),
            new StringValue('c'),
        ]);
        $items = new ListValue([new StringValue('b')]);

        self::assertEquals(
            new ListValue([new StringValue('a'), new StringValue('c')]),
            (new RemoveList('foo', $items))->applied($base),
            'RemoveList must produce a list with sequential numeric keys after removal',
        );
    }

    #[Test]
    public function rejectsBaseValueThatIsNotAList(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('phpstan.checked_exceptions');

        (new RemoveList('phpstan.checked_exceptions', new ListValue([])))
            ->applied(new IntValue(8));
    }
}
