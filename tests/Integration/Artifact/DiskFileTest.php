<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Artifact;

use Haspadar\Piqule\Artifact\DiskFile;
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

    #[Test]
    public function returnsCanonicalAbsolutePathAsId(): void
    {
        $directory = new DirectoryFixture('disk-file');
        $path = $directory->path() . '/file.txt';

        file_put_contents($path, 'test');

        $file = new DiskFile($path);

        $this->assertSame(
            realpath($path),
            $file->id(),
        );
    }

    #[Test]
    public function throwsExceptionWhenIdCannotBeResolved(): void
    {
        $file = new DiskFile('/missing/file.txt');

        $this->expectException(PiquleException::class);

        $file->id();
    }
}
