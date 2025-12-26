<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\DiskFile;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskFileTest extends TestCase
{
    #[Test]
    public function throwsExceptionWhenFileDoesNotExist(): void
    {
        $file = new DiskFile(
            (new DirectoryFixture('disk-file'))->path() . '/missing.txt',
        );

        $this->expectException(PiquleException::class);

        $file->contents();
    }
}
