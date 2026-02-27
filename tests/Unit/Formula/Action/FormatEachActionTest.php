<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\FormatEachAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FormatEachActionTest extends TestCase
{
    #[Test]
    public function formatsSingleStringValue(): void
    {
        $result = (new FormatEachAction('ext=%s'))
            ->transformed(new ListArgs(['mbstring']));

        self::assertSame(
            ['ext=mbstring'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsMultipleValues(): void
    {
        $result = (new FormatEachAction('v=%s'))
            ->transformed(new ListArgs(['a', 'b']));

        self::assertSame(
            ['v=a', 'v=b'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsBooleanValuesUsingCanonicalStringRepresentation(): void
    {
        $result = (new FormatEachAction('flag=%s'))
            ->transformed(new ListArgs([true, false]));

        self::assertSame(
            ['flag=true', 'flag=false'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsNumericValues(): void
    {
        $result = (new FormatEachAction('n=%s'))
            ->transformed(new ListArgs([10, 3.5]));

        self::assertSame(
            ['n=10', 'n=3.5'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsUsingEmptyTemplate(): void
    {
        $result = (new FormatEachAction(''))
            ->transformed(new ListArgs(['a', 'b']));

        self::assertSame(
            ['', ''],
            $result->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenInputIsEmpty(): void
    {
        $result = (new FormatEachAction('%s'))
            ->transformed(new ListArgs([]));

        self::assertSame(
            [],
            $result->values(),
        );
    }
}
