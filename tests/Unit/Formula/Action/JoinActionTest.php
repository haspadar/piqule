<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Args\ArrayArgs;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsText;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JoinActionTest extends TestCase
{
    #[Test]
    public function joinsListItemsWithDelimiter(): void
    {
        self::assertThat(
            (new JoinAction(
                new RawArgs(' | '),
            ))->apply(
                new ArrayArgs(['red', 'green', 'blue']),
            ),
            new HasArgsText('red | green | blue'),
        );
    }

    #[Test]
    public function joinsEmptyListIntoEmptyText(): void
    {
        self::assertThat(
            (new JoinAction(
                new RawArgs(','),
            ))->apply(
                new ArrayArgs([]),
            ),
            new HasArgsText(''),
        );
    }
}
