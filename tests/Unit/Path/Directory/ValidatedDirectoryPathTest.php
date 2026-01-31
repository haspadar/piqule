<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path\Directory;

use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Haspadar\Piqule\Path\Directory\ValidatedDirectoryPath;
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
            new AbsoluteDirectoryPath(''),
        ))->value();
    }

    #[Test]
    public function throwsWhenRelative(): void
    {
        $this->expectException(PiquleException::class);

        (new ValidatedDirectoryPath(
            new AbsoluteDirectoryPath('relative/path'),
        ))->value();
    }

    #[Test]
    public function throwsWhenDriveLetterIsNotAtStart(): void
    {
        $this->expectException(PiquleException::class);

        (new ValidatedDirectoryPath(
            new AbsoluteDirectoryPath('fooC:\Windows'),
        ))->value();
    }

    #[Test]
    public function allowsPosixAbsolute(): void
    {
        self::assertSame(
            '/var/www/',
            (new ValidatedDirectoryPath(
                new AbsoluteDirectoryPath('/var/www/'),
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
                new AbsoluteDirectoryPath('C:\Windows\\'),
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
                new AbsoluteDirectoryPath('\Windows\System32\\'),
            ))->value(),
            'Allows Windows UNC absolute directory paths',
        );
    }
}
