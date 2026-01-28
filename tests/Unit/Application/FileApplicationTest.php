<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Application;

use Haspadar\Piqule\Application\FileApplication;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\File\ListedFiles;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileApplicationTest extends TestCase
{
    #[Test]
    public function writesAllFilesIntoStorage(): void
    {
        $storage = new InMemoryStorage();
        (new FileApplication(
            new ListedFiles([
                new InlineFile('a.txt', 'A'),
            ]),
            $storage,
            new FakeTarget(),
        ))->run();

        self::assertSame(
            'A',
            $storage->read('a.txt'),
            'FileApplication must write file into storage',
        );
    }
}
