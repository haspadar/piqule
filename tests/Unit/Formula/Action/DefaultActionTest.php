<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\DefaultAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsValues;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultActionTest extends TestCase
{
    #[Test]
    public function keepsOriginalValuesWhenNotEmpty(): void
    {
        self::assertThat(
            (new DefaultAction('["fallback"]'))->transformed(
                new ListArgs(['alpha']),
            ),
            new HasArgsValues(['alpha']),
        );
    }

    #[Test]
    public function returnsDefaultWhenInputIsEmpty(): void
    {
        self::assertThat(
            (new DefaultAction('["fallback"]'))->transformed(
                new ListArgs([]),
            ),
            new HasArgsValues(['fallback']),
        );
    }

    #[Test]
    public function returnsMultipleDefaultValues(): void
    {
        self::assertThat(
            (new DefaultAction('["a","b"]'))->transformed(
                new ListArgs([]),
            ),
            new HasArgsValues(['a', 'b']),
        );
    }

    #[Test]
    public function supportsNumbersAndBooleans(): void
    {
        self::assertThat(
            (new DefaultAction('[1,true,false]'))->transformed(
                new ListArgs([]),
            ),
            new HasArgsValues([1, true, false]),
        );
    }
}
