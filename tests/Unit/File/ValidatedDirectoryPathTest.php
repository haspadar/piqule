<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\DirectoryPath;
use Haspadar\Piqule\File\ValidatedDirectoryPath;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValidatedDirectoryPathTest extends TestCase
{
    #[Test]
    public function throwsWhenEmpty(): void
    {
        $this->expectException(PiquleException::class);

        (new ValidatedDirectoryPath(
            new DirectoryPath(''),
        ))->value();
    }

    #[Test]
    public function throwsWhenRelative(): void
    {
        $this->expectException(PiquleException::class);

        (new ValidatedDirectoryPath(
            new DirectoryPath('relative/path'),
        ))->value();
    }

    #[Test]
    public function throwsWhenDriveLetterIsNotAtStart(): void
    {
        $this->expectException(PiquleException::class);

        (new ValidatedDirectoryPath(
            new DirectoryPath('fooC:\Windows'),
        ))->value();
    }

    #[Test]
    public function allowsPosixAbsolute(): void
    {
        self::assertSame(
            '/var/www/',
            (new ValidatedDirectoryPath(
                new DirectoryPath('/var/www/'),
            ))->value(),
            'Allows POSIX absolute directory paths',
        );
    }

    #[Test]
    public function allowsWindowsDriveAbsolute(): void
    {
        self::assertSame(
            'C:\Windows\\',
            (new ValidatedDirectoryPath(
                new DirectoryPath('C:\Windows\\'),
            ))->value(),
            'Allows Windows drive absolute directory paths',
        );
    }

    #[Test]
    public function allowsWindowsUncAbsolute(): void
    {
        self::assertSame(
            '\Windows\System32\\',
            (new ValidatedDirectoryPath(
                new DirectoryPath('\Windows\System32\\'),
            ))->value(),
            'Allows Windows UNC absolute directory paths',
        );
    }
}
