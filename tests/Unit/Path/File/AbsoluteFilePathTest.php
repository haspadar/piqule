<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path\File;

use Haspadar\Piqule\File\FileName;
use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Haspadar\Piqule\Path\File\AbsoluteFilePath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AbsoluteFilePathTest extends TestCase
{
    #[Test]
    public function returnsAbsoluteFilePath(): void
    {
        self::assertSame(
            '/var/www/file.txt',
            (new AbsoluteFilePath(
                new AbsoluteDirectoryPath('/var/www'),
                new FileName('file.txt'),
            ))->value(),
            'Composes absolute file path from directory path and file name',
        );
    }
}
