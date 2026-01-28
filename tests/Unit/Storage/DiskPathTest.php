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
            '/root/dir/file.txt',
            (new DiskPath('/root'))->full('dir/file.txt'),
            'Expected relative path to be resolved under root',
        );
    }

    #[Test]
    public function rejectsPathTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('/root'))->full('../file.txt');
    }

    #[Test]
    public function rejectsAbsolutePath(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('/root'))->full('/etc/passwd');
    }

    #[Test]
    public function rejectsNullByte(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('/root'))->full("file\0.txt");
    }

    #[Test]
    public function rejectsWindowsTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskPath('/root'))->full('..\\file.txt');
    }
}
