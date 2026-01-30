<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\FileName;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileNameTest extends TestCase
{
    #[Test]
    public function throwsWhenEmpty(): void
    {
        $this->expectException(PiquleException::class);

        (new FileName(''))->value();
    }

    #[Test]
    public function throwsWhenAbsolute(): void
    {
        $this->expectException(PiquleException::class);

        (new FileName('/etc/passwd'))->value();
    }

    #[Test]
    public function throwsWhenContainsParentTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new FileName('../secret'))->value();
    }

    #[Test]
    public function returnsRelativeName(): void
    {
        self::assertSame(
            'dir/file.txt',
            (new FileName('dir/file.txt'))->value(),
            'Returns relative file name as-is',
        );
    }

    #[Test]
    public function throwsWhenWindowsAbsolute(): void
    {
        $this->expectException(PiquleException::class);

        (new FileName('C:\\Windows\\System32'))->value();
    }
}
