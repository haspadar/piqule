<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path\Directory;

use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AbsoluteDirectoryPathTest extends TestCase
{
    #[Test]
    public function returnsRawValue(): void
    {
        self::assertSame(
            'any/path/value',
            (new AbsoluteDirectoryPath('any/path/value'))->value(),
            'Returns raw directory path value as provided',
        );
    }

    #[Test]
    public function returnsPosixRoot(): void
    {
        self::assertSame('/', (new AbsoluteDirectoryPath('/'))->value());
    }

    #[Test]
    public function returnsWindowsDriveRoot(): void
    {
        self::assertSame('C:\\', (new AbsoluteDirectoryPath('C:\\'))->value());
    }
}
