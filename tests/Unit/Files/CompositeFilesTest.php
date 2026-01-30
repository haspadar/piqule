<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Files;

use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Files\CompositeFiles;
use Haspadar\Piqule\Files\ListedFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CompositeFilesTest extends TestCase
{
    #[Test]
    public function combinesFilesFromAllSources(): void
    {
        $files = new CompositeFiles([
            new ListedFiles([
                new InlineFile('a.txt', 'A'),
            ]),
            new ListedFiles([
                new InlineFile('b.txt', 'B'),
            ]),
        ]);

        self::assertEquals(
            [
                new InlineFile('a.txt', 'A'),
                new InlineFile('b.txt', 'B'),
            ],
            [...$files->all()],
            'Files were not combined in source order',
        );
    }
}
