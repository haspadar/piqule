<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\TextFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TextFileTest extends TestCase
{
    #[Test]
    public function exposesProvidedName(): void
    {
        self::assertSame(
            'path/to/file.txt',
            (new TextFile(
                'path/to/file.txt',
                'contents',
            ))->name(),
            'File must expose provided relative path',
        );
    }

    #[Test]
    public function exposesProvidedContents(): void
    {
        self::assertSame(
            'hello world',
            (new TextFile(
                'file.txt',
                'hello world',
            ))->contents(),
            'File must expose provided contents',
        );
    }
}
