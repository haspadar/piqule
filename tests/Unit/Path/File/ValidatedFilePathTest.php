<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path\File;

use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Haspadar\Piqule\Path\File\AbsoluteFilePath;
use Haspadar\Piqule\Path\File\ValidatedFilePath;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Source\FileName;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValidatedFilePathTest extends TestCase
{
    #[Test]
    public function throwsWhenNameEndsWithSeparator(): void
    {
        $this->expectException(PiquleException::class);

        (new ValidatedFilePath(
            new AbsoluteFilePath(
                new AbsoluteDirectoryPath('/var/www'),
                new FileName('dir/'),
            ),
        ))->value();
    }
}
