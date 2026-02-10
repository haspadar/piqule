<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Args;

use Haspadar\Piqule\Formula\Args\RawArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsText;
use LogicException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RawArgsTest extends TestCase
{
    #[Test]
    public function returnsTextWhenRawValueGiven(): void
    {
        self::assertThat(
            new RawArgs(' raw '),
            new HasArgsText(' raw '),
            'RawArgs must return text as-is',
        );
    }

    #[Test]
    public function throwsWhenListRequested(): void
    {
        $this->expectException(LogicException::class);

        (new RawArgs('[a,b]'))->list();
    }
}
