<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsValues;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JoinActionTest extends TestCase
{
    #[Test]
    public function joinsValuesWithDelimiter(): void
    {
        self::assertThat(
            (new JoinAction(' | '))
                ->transformed(new ListArgs(['red', 'green', 'blue'])),
            new HasArgsValues(['red | green | blue']),
        );
    }

    #[Test]
    public function joinsUsingEmptyDelimiter(): void
    {
        self::assertThat(
            (new JoinAction(''))
                ->transformed(new ListArgs(['a', 'b'])),
            new HasArgsValues(['ab']),
        );
    }

    #[Test]
    public function joinsSingleValue(): void
    {
        self::assertThat(
            (new JoinAction(','))
                ->transformed(new ListArgs(['one'])),
            new HasArgsValues(['one']),
        );
    }

    #[Test]
    public function returnsEmptyStringWhenInputIsEmpty(): void
    {
        self::assertThat(
            (new JoinAction(','))
                ->transformed(new ListArgs([])),
            new HasArgsValues(['']),
        );
    }
}