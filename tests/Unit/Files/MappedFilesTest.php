<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Files;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Files\ListedFiles;
use Haspadar\Piqule\Files\MappedFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MappedFilesTest extends TestCase
{
    #[Test]
    public function mapsFilesUsingProvidedClosure(): void
    {
        self::assertSame(
            'mapped.txt',
            iterator_to_array(
                (new MappedFiles(
                    new ListedFiles([
                        new InlineFile('original.txt', 'data'),
                    ]),
                    static fn(File $file) => new InlineFile('mapped.txt', $file->contents()),
                ))->all(),
            )[0]->name(),
            'MappedFiles must apply mapping closure to each File',
        );
    }
}
