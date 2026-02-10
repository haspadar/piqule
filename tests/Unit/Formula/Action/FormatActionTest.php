<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsText;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FormatActionTest extends TestCase
{
    #[Test]
    public function formatsScalarTextUsingTemplate(): void
    {
        self::assertThat(
            (new FormatAction(
                new RawArgs('<%s>'),
            ))->apply(
                new RawArgs('value'),
            ),
            new HasArgsText('<value>'),
        );
    }

    #[Test]
    public function formatsEmptyScalarText(): void
    {
        self::assertThat(
            (new FormatAction(
                new RawArgs('[%s]'),
            ))->apply(
                new RawArgs(''),
            ),
            new HasArgsText('[]'),
        );
    }
}
