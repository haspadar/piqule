<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\ExecutableFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use Haspadar\Piqule\Tests\Unit\Fake\FileSystem\AlwaysExecutableFileSystem;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ExecutableFileTest extends TestCase
{
    #[Test]
    public function delegatesName(): void
    {
        self::assertSame(
            'hook.sh',
            (new ExecutableFile(
                new InlineFile('hook.sh', 'data'),
            ))->name(),
        );
    }

    #[Test]
    public function delegatesContents(): void
    {
        self::assertSame(
            'data',
            (new ExecutableFile(
                new InlineFile('hook.sh', 'data'),
            ))->contents(),
        );
    }

    #[Test]
    public function writesFileToFileSystem(): void
    {
        $fs = new InMemoryFileSystem();

        (new ExecutableFile(
            new InlineFile('hook.sh', 'payload'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'payload',
            $fs->read('hook.sh'),
        );
    }

    #[Test]
    public function emitsExecutableAlreadySetWhenFileSystemReportsExecutable(): void
    {
        $fs = new AlwaysExecutableFileSystem(
            new InMemoryFileSystem([
                'hook.sh' => 'payload',
            ]),
        );

        $reaction = new FakeFileReaction();

        (new ExecutableFile(
            new InlineFile('hook.sh', 'payload'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            ['hook.sh'],
            $reaction->events(),
            'Expected executableAlreadySet to be emitted',
        );
    }

    #[Test]
    public function emitsExecutableWasSetWhenExecutableIsSet(): void
    {
        $fs = new InMemoryFileSystem();
        $reaction = new FakeFileReaction();

        (new ExecutableFile(
            new InlineFile('hook.sh', 'payload'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            ['hook.sh'],
            $reaction->events(),
            'Expected executableWasSet to be emitted',
        );
    }
}
