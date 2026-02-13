<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\DefaultAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultActionTest extends TestCase
{
    #[Test]
    public function returnsOriginalArgsWhenValuesNotEmpty(): void
    {
        $original = new ListArgs(['alpha']);

        $result = (new DefaultAction(
            new ListArgs(['fallback']),
        ))->transformed($original);

        self::assertSame(
            ['alpha'],
            $result->values(),
        );
    }

    #[Test]
    public function returnsDefaultArgsWhenValuesEmpty(): void
    {
        $default = new ListArgs(['fallback']);

        $result = (new DefaultAction($default))
            ->transformed(new ListArgs([]));

        self::assertSame(
            ['fallback'],
            $result->values(),
        );
    }

    #[Test]
    public function supportsMultipleDefaultValues(): void
    {
        $default = new ListArgs(['a', 'b']);

        $result = (new DefaultAction($default))
            ->transformed(new ListArgs([]));

        self::assertSame(
            ['a', 'b'],
            $result->values(),
        );
    }
}
