<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiskPath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskPathTest extends TestCase
{
    #[Test]
    public function resolvesRelativePath(): void
    {
        self::assertSame(
            '/app/root/dir/file.txt',
            (new DiskPath('/app/root'))->full('dir/file.txt'),
            'Expected relative path to be resolved under root',
        );
    }

    #[Test]
    public function rejectsPathTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('/safe/root'))->full('../file.txt');
    }

    #[Test]
    public function rejectsAbsolutePath(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('/sandbox'))->full('/etc/passwd');
    }

    #[Test]
    public function rejectsNullByte(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('/tmp/root'))->full("file\0.txt");
    }

    #[Test]
    public function rejectsWindowsTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('C:/root'))->full('..\\file.txt');
    }

    #[Test]
    public function returnsRootWithoutTrailingSlash(): void
    {
        self::assertSame(
            '/app/root',
            (new DiskPath('/app/root/'))->root(),
            'Expected trailing slash to be trimmed from root',
        );
    }

    #[Test]
    public function normalizesRootWithTrailingSlashWhenBuildingFullPath(): void
    {
        self::assertSame(
            '/app/root/file.txt',
            (new DiskPath('/app/root/'))->full('file.txt'),
            'Expected trailing slash in root to be normalized in full path',
        );
    }
}
