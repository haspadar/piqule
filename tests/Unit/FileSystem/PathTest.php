<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\FileSystem;

use Haspadar\Piqule\FileSystem\Path;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase
{
    #[Test]
    public function resolvesRelativePath(): void
    {
        self::assertSame(
            '/app/root/dir/file.txt',
            (new Path('/app/root'))->full('dir/file.txt'),
            'Expected relative path to be resolved under root',
        );
    }

    #[Test]
    public function rejectsPathTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new Path('/safe/root'))->full('../file.txt');
    }

    #[Test]
    public function rejectsAbsolutePath(): void
    {
        $this->expectException(PiquleException::class);

        (new Path('/sandbox'))->full('/etc/passwd');
    }

    #[Test]
    public function rejectsNullByte(): void
    {
        $this->expectException(PiquleException::class);

        (new Path('/tmp/root'))->full("file\0.txt");
    }

    #[Test]
    public function rejectsWindowsAbsolutePathWithForwardSlashes(): void
    {
        $this->expectException(PiquleException::class);

        (new Path('D:/sandbox'))->full('C:/Windows/System32');
    }

    #[Test]
    public function returnsRootWithoutTrailingSlash(): void
    {
        self::assertSame(
            '/app/root',
            (new Path('/app/root/'))->root(),
            'Expected trailing slash to be trimmed from root',
        );
    }

    #[Test]
    public function normalizesRootWithTrailingSlashWhenBuildingFullPath(): void
    {
        self::assertSame(
            '/app/root/file.txt',
            (new Path('/app/root/'))->full('file.txt'),
            'Expected trailing slash in root to be normalized in full path',
        );
    }

    #[Test]
    public function rejectsWindowsAbsolutePath(): void
    {
        $this->expectException(PiquleException::class);

        (new Path('C:/app/root'))->full('C:\\Windows\\System32');
    }

    #[Test]
    public function rejectsUncPath(): void
    {
        $this->expectException(PiquleException::class);

        (new Path('Z:/storage'))->full('\\\\server\\share');
    }
}
