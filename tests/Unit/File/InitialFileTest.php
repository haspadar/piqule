<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\FakeStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InitialFileTest extends TestCase
{
    #[Test]
    public function delegatesName(): void
    {
        self::assertSame(
            'example.txt',
            (new InitialFile(
                new InlineFile('example.txt', 'hello'),
            ))->name(),
        );
    }

    #[Test]
    public function delegatesContents(): void
    {
        self::assertSame(
            'hello',
            (new InitialFile(
                new InlineFile('example.txt', 'hello'),
            ))->contents(),
        );
    }

    #[Test]
    public function writesFileWhenItDoesNotExist(): void
    {
        $storage = new FakeStorage();

        (new InitialFile(
            new InlineFile('example.txt', 'hello'),
        ))->writeTo($storage, new FakeTarget());

        self::assertSame(
            'hello',
            $storage->read('example.txt'),
        );
    }

    #[Test]
    public function doesNotOverwriteExistingFile(): void
    {
        $storage = new FakeStorage([
            'example.txt' => 'original',
        ]);

        (new InitialFile(
            new InlineFile('example.txt', 'new'),
        ))->writeTo($storage, new FakeTarget());

        self::assertSame(
            'original',
            $storage->read('example.txt'),
        );
    }
}
