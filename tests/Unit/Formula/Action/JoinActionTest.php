<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsValues;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JoinActionTest extends TestCase
{
    #[Test]
    public function joinsListItemsWithDelimiter(): void
    {
        self::assertThat(
            (new JoinAction(
                new ListArgs([' | ']),
            ))->transformed(
                new ListArgs(['red', 'green', 'blue']),
            ),
            new HasArgsValues(['red | green | blue']),
        );
    }

    #[Test]
    public function joinsEmptyListIntoEmptyText(): void
    {
        self::assertThat(
            (new JoinAction(
                new ListArgs([',']),
            ))->transformed(
                new ListArgs([]),
            ),
            new HasArgsValues(['']),
        );
    }

    #[Test]
    public function throwsWhenDelimiterIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (static function (): void {
            new JoinAction(
                new ListArgs([]),
            );
        })();
    }

    #[Test]
    public function throwsWhenDelimiterHasMultipleValues(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (static function (): void {
            new JoinAction(
                new ListArgs([',', ';']),
            );
        })();
    }
}
