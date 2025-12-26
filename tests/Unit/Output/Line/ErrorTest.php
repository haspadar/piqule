<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Output\Line;

use Haspadar\Piqule\Output\Line\Error;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ErrorTest extends TestCase
{
    #[Test]
    public function writesToStderr(): void
    {
        self::assertSame(
            STDERR,
            (new Error('boom'))->stream(),
            'Error line must be written to STDERR',
        );
    }
}
