<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsValues;
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
            'FormatAction must apply the template to a single input value',
        );
    }

    #[Test]
    public function returnsEmptyListWhenInputIsEmpty(): void
    {
        self::assertThat(
            (new FormatAction('%s'))->transformed(new ListArgs([])),
            new HasArgsValues([]),
            'FormatAction must pass empty list through unchanged',
        );
    }

    #[Test]
    public function throwsWhenInputContainsMultipleValues(): void
    {
        $this->expectException(PiquleException::class);

        (new FormatAction('%s'))
            ->transformed(new ListArgs(['a', 'b']));
    }

    #[Test]
    public function normalizesEscapeSequencesInTemplate(): void
    {
        $result = (new FormatAction('a\\n%s'))
            ->transformed(new ListArgs(['b']));

        self::assertSame(
            ["a\nb"],
            $result->values(),
            'FormatAction must normalize \\n to real newline in template',
        );
    }

    #[Test]
    public function throwsWhenSprintfFails(): void
    {
        $this->expectException(PiquleException::class);

        (new FormatAction('%1$s %2$s'))
            ->transformed(new ListArgs(['only-one']));
    }
}
