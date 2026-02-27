<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\DefaultScalarAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultScalarActionTest extends TestCase
{
    #[Test]
    public function returnsProvidedDefaultMemoryWhenInputIsEmpty(): void
    {
        $result = (new DefaultScalarAction('"512M"'))
            ->transformed(new ListArgs([]));

        self::assertSame(
            ['512M'],
            $result->values(),
        );
    }

    #[Test]
    public function keepsConfiguredTimeoutWhenSingleValueIsPresent(): void
    {
        $result = (new DefaultScalarAction('"30s"'))
            ->transformed(new ListArgs(['45s']));

        self::assertSame(
            ['45s'],
            $result->values(),
        );
    }

    #[Test]
    public function throwsWhenMultipleLogLevelsAreProvided(): void
    {
        $this->expectException(PiquleException::class);

        (new DefaultScalarAction('"info"'))
            ->transformed(new ListArgs(['debug', 'warning']));
    }
}
