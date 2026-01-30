<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path;

use Haspadar\Piqule\Path\DirectoryPath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DirectoryPathTest extends TestCase
{
    #[Test]
    public function returnsRawValue(): void
    {
        self::assertSame(
            'any/path/value',
            (new DirectoryPath('any/path/value'))->value(),
            'Returns raw directory path value as provided',
        );
    }

    #[Test]
    public function returnsPosixRoot(): void
    {
        self::assertSame('/', (new DirectoryPath('/'))->value());
    }

    #[Test]
    public function returnsWindowsDriveRoot(): void
    {
        self::assertSame('C:\\', (new DirectoryPath('C:\\'))->value());
    }
}
