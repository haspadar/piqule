<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Files;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Files\FolderFiles;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Constraint\Files\HasFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FolderFilesTest extends TestCase
{
    #[Test]
    public function exposesFilesFromStorageEntries(): void
    {
        self::assertThat(
            new FolderFiles(
                new InMemoryStorage([
                    'a/one.txt' => new TextFile('a/one.txt', '1'),
                    'a/two.txt' => new TextFile('a/two.txt', '2'),
                ]),
                'a',
            ),
            new HasFiles([
                'a/one.txt' => '1',
                'a/two.txt' => '2',
            ]),
            'FolderFiles must expose all files from the given storage folder',
        );
    }
}
