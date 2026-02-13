<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\ScalarAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ScalarActionTest extends TestCase
{
    #[Test]
    public function returnsSingleValueWhenExactlyOnePresent(): void
    {
        $result = (new ScalarAction())->transformed(
            new ListArgs(['alpha']),
        );

        self::assertSame(
            ['alpha'],
            $result->values(),
        );
    }

    #[Test]
    public function returnsEmptyListWhenNoValuesPresent(): void
    {
        $result = (new ScalarAction())->transformed(
            new ListArgs([]),
        );

        self::assertSame(
            [],
            $result->values(),
        );
    }

    #[Test]
    public function throwsWhenMoreThanOneValuePresent(): void
    {
        $this->expectException(PiquleException::class);

        (new ScalarAction())->transformed(
            new ListArgs(['a', 'b']),
        );
    }
}
