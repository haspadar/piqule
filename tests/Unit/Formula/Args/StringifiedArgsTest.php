<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StringifiedArgsTest extends TestCase
{
    #[Test]
    public function convertsBooleanTrueToLiteralTrue(): void
    {
        $args = new StringifiedArgs(
            new ListArgs([true]),
        );

        self::assertSame(
            ['true'],
            $args->values(),
        );
    }

    #[Test]
    public function convertsBooleanFalseToLiteralFalse(): void
    {
        $args = new StringifiedArgs(
            new ListArgs([false]),
        );

        self::assertSame(
            ['false'],
            $args->values(),
        );
    }

    #[Test]
    public function convertsMixedValuesToStrings(): void
    {
        $args = new StringifiedArgs(
            new ListArgs([
                true,
                false,
                10,
                3.14,
                'x',
            ]),
        );

        self::assertSame(
            ['true', 'false', '10', '3.14', 'x'],
            $args->values(),
        );
    }
}
