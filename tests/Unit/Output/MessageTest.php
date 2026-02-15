<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Output;

use Haspadar\Piqule\Output\Message;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    #[Test]
    public function hasGivenBody(): void
    {
        self::assertSame(
            'hello',
            (new Message('hello'))->body(),
        );
    }
}
