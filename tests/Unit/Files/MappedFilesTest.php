<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Files;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\ReplacedFile;
use Haspadar\Piqule\Files\MappedFiles;
use Haspadar\Piqule\Files\TextFiles;
use Haspadar\Piqule\Tests\Constraint\Files\HasFiles;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MappedFilesTest extends TestCase
{
    #[Test]
    public function mapsAllFiles(): void
    {
        self::assertThat(
            new MappedFiles(
                new TextFiles([
                    'README.md' => 'Hello, {{name}}',
                    'config/app.ini' => 'name={{name}}',
                ]),
                fn(File $file) => new ReplacedFile(
                    $file,
                    '{{name}}',
                    'Piqule',
                ),
            ),
            new HasFiles([
                'README.md' => 'Hello, Piqule',
                'config/app.ini' => 'name=Piqule',
            ]),
        );
    }
}
