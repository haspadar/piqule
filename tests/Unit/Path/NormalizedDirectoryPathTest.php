<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path;

use Haspadar\Piqule\Path\DirectoryPath;
use Haspadar\Piqule\Path\NormalizedDirectoryPath;
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
                new DirectoryPath('/'),
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
                new DirectoryPath('C:\\'),
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
                new DirectoryPath('/var/www/'),
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
                new DirectoryPath('C:\Windows\\'),
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
                new DirectoryPath('\Windows\System32\\'),
            ))->value(),
            'Removes trailing slash from Windows UNC directory path',
        );
    }
}
