<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\DirectoryPath;
use Haspadar\Piqule\File\NormalizedDirectoryPath;
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
    public function trimsTrailingSlashFromWindowsUncPath(): void
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
