<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Files;

use Haspadar\Piqule\File\TempFolder;
use Haspadar\Piqule\Files\FolderFiles;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Constraint\Files\HasFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FolderFilesTest extends TestCase
{
    #[Test]
    public function exposesOnlyFilesFromGivenFolder(): void
    {
        self::assertThat(
            new FolderFiles(
                new DiskStorage(
                    (new TempFolder())
                        ->withFile('a/one.txt', '1')
                        ->withFile('a/two.txt', '2')
                        ->withFile('b/skip.txt', 'x')
                        ->path(),
                ),
                'a',
            ),
            new HasFiles([
                'a/one.txt' => '1',
                'a/two.txt' => '2',
            ]),
        );
    }
}
