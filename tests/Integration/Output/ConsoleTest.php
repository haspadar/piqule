<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Output;

use Haspadar\Piqule\Tests\Fixture\ConsoleProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConsoleTest extends TestCase
{
    #[Test]
    public function writesYellowTextToStdoutOnInfo(): void
    {
        self::assertSame(
            "\033[33mhello\033[0m\n",
            (new ConsoleProcess('info', 'hello'))->stdout(),
            'info() must write yellow ANSI text to stdout',
        );
    }

    #[Test]
    public function writesGreenTextToStdoutOnSuccess(): void
    {
        self::assertSame(
            "\033[32mdone\033[0m\n",
            (new ConsoleProcess('success', 'done'))->stdout(),
            'success() must write green ANSI text to stdout',
        );
    }

    #[Test]
    public function writesRedTextToStderrOnError(): void
    {
        self::assertSame(
            "\033[31mfail\033[0m\n",
            (new ConsoleProcess('error', 'fail'))->stderr(),
            'error() must write red ANSI text to stderr',
        );
    }

    #[Test]
    public function writesGrayTextToStdoutOnMuted(): void
    {
        self::assertSame(
            "\033[90mskip\033[0m\n",
            (new ConsoleProcess('muted', 'skip'))->stdout(),
            'muted() must write gray ANSI text to stdout',
        );
    }
}
