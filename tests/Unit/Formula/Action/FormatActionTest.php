<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FormatActionTest extends TestCase
{
    #[Test]
    public function formatsSingleValue(): void
    {
        $result = (new FormatAction('prefix: %s'))
            ->transformed(new ListArgs(['value']));

        self::assertSame(
            ['prefix: value'],
            $result->values(),
        );
    }

    #[Test]
    public function throwsWhenListHasMultipleValues(): void
    {
        $this->expectException(PiquleException::class);

        (new FormatAction('%s'))
            ->transformed(new ListArgs(['a', 'b']));
    }

    #[Test]
    public function throwsWhenInputIsEmpty(): void
    {
        $this->expectException(PiquleException::class);

        (new FormatAction('%s'))
            ->transformed(new ListArgs([]));
    }

    #[Test]
    public function throwsWhenInputContainsMultipleValues(): void
    {
        $this->expectException(PiquleException::class);

        (new FormatAction('%s'))
            ->transformed(new ListArgs(['a', 'b']));
    }
}
