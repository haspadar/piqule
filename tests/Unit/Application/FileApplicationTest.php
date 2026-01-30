<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Application;

use Haspadar\Piqule\Application\FileApplication;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\File\ListedFiles;
use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileApplicationTest extends TestCase
{
    #[Test]
    public function writesAllFilesIntoFileSystem(): void
    {
        $fs = new InMemoryFileSystem();

        (new FileApplication(
            new ListedFiles([
                new InlineFile('a.txt', 'A'),
            ]),
            $fs,
            new FakeFileReaction(),
        ))->run();

        self::assertSame(
            'A',
            $fs->read('a.txt'),
            'FileApplication must write file into filesystem',
        );
    }
}
