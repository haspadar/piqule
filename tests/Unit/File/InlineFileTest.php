<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\InlineFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InlineFileTest extends TestCase
{
    #[Test]
    public function returnsProvidedName(): void
    {
        $file = new InlineFile('example.txt', 'hello');

        self::assertSame(
            'example.txt',
            $file->name(),
        );
    }

    #[Test]
    public function returnsProvidedContents(): void
    {
        $file = new InlineFile('example.txt', 'hello');

        self::assertSame(
            'hello',
            $file->contents(),
        );
    }
}
