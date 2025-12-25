<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests;

use Haspadar\Piqule\CommandLine;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CommandLineTest extends TestCase
{
    #[Test]
    public function returnsCommand(): void
    {
        self::assertSame(
            'sync',
            (new CommandLine(['piqule', 'sync']))->command(),
            'Command must be extracted from argv',
        );
    }
}
