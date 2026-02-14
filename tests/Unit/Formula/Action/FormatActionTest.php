<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FormatActionTest extends TestCase
{
    #[Test]
    public function formatsSingleStringValue(): void
    {
        $result = (new FormatAction('ext=%s'))
            ->transformed(new ListArgs(['mbstring']));

        self::assertSame(
            ['ext=mbstring'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsMultipleValues(): void
    {
        $result = (new FormatAction('v=%s'))
            ->transformed(new ListArgs(['a', 'b']));

        self::assertSame(
            ['v=a', 'v=b'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsBooleanValuesUsingCanonicalStringRepresentation(): void
    {
        $result = (new FormatAction('flag=%s'))
            ->transformed(new ListArgs([true, false]));

        self::assertSame(
            ['flag=true', 'flag=false'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsNumericValues(): void
    {
        $result = (new FormatAction('n=%s'))
            ->transformed(new ListArgs([10, 3.5]));

        self::assertSame(
            ['n=10', 'n=3.5'],
            $result->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenTemplateIsEmpty(): void
    {
        $result = (new FormatAction(''))
            ->transformed(new ListArgs(['a', 'b']));

        self::assertSame(
            [],
            $result->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenInputIsEmpty(): void
    {
        $result = (new FormatAction('%s'))
            ->transformed(new ListArgs([]));

        self::assertSame(
            [],
            $result->values(),
        );
    }
}