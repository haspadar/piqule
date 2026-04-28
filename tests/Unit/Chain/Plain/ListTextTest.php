<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Chain\Plain;

use Haspadar\Piqule\Chain\Plain\BoolText;
use Haspadar\Piqule\Chain\Plain\FloatText;
use Haspadar\Piqule\Chain\Plain\IntText;
use Haspadar\Piqule\Chain\Plain\ListText;
use Haspadar\Piqule\Chain\Plain\StringText;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\FloatValue;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\ListValue;
use Haspadar\Piqule\Settings\Value\StringValue;
use Haspadar\Piqule\Settings\Value\TreeValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ListTextTest extends TestCase
{
    #[Test]
    public function wrapsStringChildrenIntoStringTextParts(): void
    {
        self::assertContainsOnlyInstancesOf(
            StringText::class,
            (new ListText(new ListValue([
                new StringValue('src'),
                new StringValue('tests'),
            ])))->parts(),
            'ListText must wrap StringValue children into StringText parts',
        );
    }

    #[Test]
    public function dispatchesEachScalarChildToItsMatchingPlainOp(): void
    {
        $parts = (new ListText(new ListValue([
            new BoolValue(true),
            new IntValue(8),
            new FloatValue(0.5),
        ])))->parts();

        self::assertSame(
            [BoolText::class, IntText::class, FloatText::class],
            [$parts[0]::class, $parts[1]::class, $parts[2]::class],
            'ListText must dispatch each scalar child to its matching Plain op type',
        );
    }

    #[Test]
    public function returnsEmptyPartsForEmptyListValue(): void
    {
        self::assertSame(
            [],
            (new ListText(new ListValue([])))->parts(),
            'ListText must return no parts when the source list is empty',
        );
    }

    #[Test]
    public function refusesDirectRendering(): void
    {
        $this->expectException(PiquleException::class);

        (new ListText(new ListValue([new StringValue('src')])))->rendered();
    }

    #[Test]
    public function rejectsNestedTreeChildren(): void
    {
        $this->expectException(PiquleException::class);

        (new ListText(new ListValue([new TreeValue([])])))->parts();
    }

    #[Test]
    public function rejectsNestedListChildren(): void
    {
        $this->expectException(PiquleException::class);

        (new ListText(new ListValue([new ListValue([])])))->parts();
    }
}
