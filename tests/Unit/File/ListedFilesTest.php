<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\File\ListedFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ListedFilesTest extends TestCase
{
    #[Test]
    public function returnsFilesInOriginalOrder(): void
    {
        self::assertSame(
            ['a.txt', 'b.txt'],
            array_map(
                static fn ($file) => $file->name(),
                iterator_to_array(
                    (new ListedFiles([
                        new InlineFile('a.txt', 'A'),
                        new InlineFile('b.txt', 'B'),
                    ]))->all(),
                ),
            ),
            'ListedFiles must return provided files in original order',
        );
    }
}
