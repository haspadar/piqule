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
    public function formatsSingleValue(): void
    {
        $result = (new FormatAction(
            new ListArgs(['ext=%s']),
        ))->transformed(
            new ListArgs(['mbstring']),
        );

        self::assertSame(
            ['ext=mbstring'],
            $result->values(),
        );
    }

    #[Test]
    public function formatsAllValuesWhenMultipleGiven(): void
    {
        $result = (new FormatAction(
            new ListArgs(['%s']),
        ))->transformed(
            new ListArgs(['alpha', 'beta']),
        );

        self::assertSame(
            ['alpha', 'beta'],
            $result->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenInputIsEmpty(): void
    {
        $result = (new FormatAction(
            new ListArgs(['%s']),
        ))->transformed(
            new ListArgs([]),
        );

        self::assertSame(
            [],
            $result->values(),
        );
    }
}
