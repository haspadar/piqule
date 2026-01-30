<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path;

use Haspadar\Piqule\File\FileName;
use Haspadar\Piqule\Path\DirectoryPath;
use Haspadar\Piqule\Path\FilePath;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FilePathTest extends TestCase
{
    #[Test]
    public function returnsAbsoluteFilePath(): void
    {
        self::assertSame(
            '/var/www/file.txt',
            (new FilePath(
                new DirectoryPath('/var/www'),
                new FileName('file.txt'),
            ))->value(),
            'Composes absolute file path from directory path and file name',
        );
    }

    #[Test]
    public function throwsWhenNameEndsWithSeparator(): void
    {
        $this->expectException(PiquleException::class);

        (new FilePath(
            new DirectoryPath('/var/www'),
            new FileName('dir/'),
        ))->value();
    }
}
