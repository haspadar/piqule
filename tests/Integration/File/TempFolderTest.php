<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TempFolderTest extends TestCase
{
    #[Test]
    public function createsDirectoryOnConstruction(): void
    {
        self::assertDirectoryExists(
            (new TempFolder())->path(),
            'TempFolder must create directory on construction',
        );
    }

    #[Test]
    public function createsFilesInsideFolder(): void
    {
        $folder = new TempFolder();
        $folder->withFile('a/b.txt', 'data');

        self::assertFileExists(
            $folder->path() . '/a/b.txt',
            'TempFolder must create files relative to its own path',
        );
    }

    #[Test]
    public function removesDirectoryOnClose(): void
    {
        $folder = new TempFolder();
        $path = $folder->path();

        $folder->close();

        self::assertDirectoryDoesNotExist(
            $path,
            'TempFolder must remove directory on close',
        );
    }
}
