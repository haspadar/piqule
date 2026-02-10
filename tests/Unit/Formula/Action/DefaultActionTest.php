<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\DefaultAction;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsText;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultActionTest extends TestCase
{
    #[Test]
    public function returnsOriginalArgsWhenScalarIsNotEmpty(): void
    {
        self::assertThat(
            (new DefaultAction(
                new RawArgs('fallback'),
            ))->apply(new RawArgs('value')),
            new HasArgsText('value'),
        );
    }

    #[Test]
    public function returnsDefaultArgsWhenScalarIsEmpty(): void
    {
        self::assertThat(
            (new DefaultAction(
                new RawArgs('fallback'),
            ))->apply(new RawArgs('')),
            new HasArgsText('fallback'),
        );
    }
}
