<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\FormatEachAction;
use Haspadar\Piqule\Formula\Args\ArrayArgs;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsList;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FormatEachActionTest extends TestCase
{
    #[Test]
    public function formatsEachListItem(): void
    {
        self::assertThat(
            (new FormatEachAction(
                new RawArgs('(%s)'),
            ))->apply(
                new ArrayArgs(['a', 'b']),
            ),
            new HasArgsList(['(a)', '(b)']),
        );
    }

    #[Test]
    public function keepsEmptyListEmpty(): void
    {
        self::assertThat(
            (new FormatEachAction(
                new RawArgs('<%s>'),
            ))->apply(new ArrayArgs([])),
            new HasArgsList([]),
        );
    }
}
