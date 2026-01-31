<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path\File;

use Haspadar\Piqule\File\FileName;
use Haspadar\Piqule\Path\File\RelativeFilePath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RelativeFilePathTest extends TestCase
{
    #[Test]
    public function returnsFileNameAsPath(): void
    {
        self::assertSame(
            'file.txt',
            (new RelativeFilePath(
                new FileName('file.txt'),
            ))->value(),
            'Expected relative file path to equal file name',
        );
    }
}
