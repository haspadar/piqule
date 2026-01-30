<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Path;

use Haspadar\Piqule\File\FileName;
use Haspadar\Piqule\Path\DirectoryPath;
use Haspadar\Piqule\Path\FilePath;
use Haspadar\Piqule\Path\ValidatedFilePath;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValidatedFilePathTest extends TestCase
{
    #[Test]
    public function throwsWhenNameEndsWithSeparator(): void
    {
        $this->expectException(PiquleException::class);

        (new ValidatedFilePath(
            new FilePath(
                new DirectoryPath('/var/www'),
                new FileName('dir/'),
            ),
        ))->value();
    }
}
