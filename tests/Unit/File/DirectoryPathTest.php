<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\DirectoryPath;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DirectoryPathTest extends TestCase
{
    #[Test]
    public function throwsWhenEmpty(): void
    {
        $this->expectException(PiquleException::class);

        (new DirectoryPath(''))->value();
    }

    #[Test]
    public function throwsWhenRelative(): void
    {
        $this->expectException(PiquleException::class);

        (new DirectoryPath('relative/path'))->value();
    }

    #[Test]
    public function returnsPosixAbsolute(): void
    {
        self::assertSame(
            '/var/www',
            (new DirectoryPath('/var/www/'))->value(),
            'Accepts POSIX absolute directory paths',
        );
    }

    #[Test]
    public function returnsWindowsDriveAbsolute(): void
    {
        self::assertSame(
            'C:\Windows',
            (new DirectoryPath('C:\Windows\\'))->value(),
            'Accepts Windows drive absolute directory paths',
        );
    }

    #[Test]
    public function returnsWindowsUncAbsolute(): void
    {
        self::assertSame(
            '\Windows\System32',
            (new DirectoryPath('\Windows\System32\\'))->value(),
            'Accepts Windows UNC absolute directory paths',
        );
    }
}
