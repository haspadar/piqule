<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path\Directory;

use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Haspadar\Piqule\Path\Directory\NormalizedDirectoryPath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NormalizedDirectoryPathTest extends TestCase
{
    #[Test]
    public function keepsPosixRoot(): void
    {
        self::assertSame(
            '/',
            (new NormalizedDirectoryPath(
                new AbsoluteDirectoryPath('/'),
            ))->value(),
            'Keeps POSIX root directory path',
        );
    }

    #[Test]
    public function keepsWindowsDriveRoot(): void
    {
        self::assertSame(
            'C:\\',
            (new NormalizedDirectoryPath(
                new AbsoluteDirectoryPath('C:\\'),
            ))->value(),
            'Keeps Windows drive root directory path',
        );
    }

    #[Test]
    public function trimsTrailingSlashFromPosixPath(): void
    {
        self::assertSame(
            '/var/www',
            (new NormalizedDirectoryPath(
                new AbsoluteDirectoryPath('/var/www/'),
            ))->value(),
            'Removes trailing slash from POSIX directory path',
        );
    }

    #[Test]
    public function trimsTrailingSlashFromWindowsPath(): void
    {
        self::assertSame(
            'C:\Windows',
            (new NormalizedDirectoryPath(
                new AbsoluteDirectoryPath('C:\Windows\\'),
            ))->value(),
            'Removes trailing slash from Windows directory path',
        );
    }

    #[Test]
    public function trimsTrailingSlashFromWindowsRootedPath(): void
    {
        self::assertSame(
            '\Windows\System32',
            (new NormalizedDirectoryPath(
                new AbsoluteDirectoryPath('\Windows\System32\\'),
            ))->value(),
            'Removes trailing slash from Windows rooted directory path',
        );
    }
}
