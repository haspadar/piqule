<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Output;

use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Console;
use Haspadar\Piqule\Tests\Fake\Output\Line\StreamTextLine;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConsoleTest extends TestCase
{
    #[Test]
    public function writesLineToProvidedStream(): void
    {
        $stream = fopen('php://temp', 'wb+');

        (new Console())->write(
            new StreamTextLine('hello', new Grey(), $stream),
        );
        rewind($stream);

        self::assertNotEmpty(
            stream_get_contents($stream),
            'Expected console to write something to stream',
        );
    }
}
