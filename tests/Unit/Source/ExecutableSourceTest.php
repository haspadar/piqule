<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source;

use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Source\ExecutableSource;
use Haspadar\Piqule\Source\InlineSource;
use Haspadar\Piqule\Tests\Unit\Fake\FileSystem\AlwaysExecutableFileSystem;
use Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ExecutableSourceTest extends TestCase
{
    #[Test]
    public function delegatesName(): void
    {
        self::assertSame(
            'hook.sh',
            (new ExecutableSource(
                new InlineSource('hook.sh', 'data'),
            ))->name(),
        );
    }

    #[Test]
    public function delegatesContents(): void
    {
        self::assertSame(
            'data',
            (new ExecutableSource(
                new InlineSource('hook.sh', 'data'),
            ))->contents(),
        );
    }

    #[Test]
    public function writesFileToFileSystem(): void
    {
        $fs = new InMemoryFileSystem();

        (new ExecutableSource(
            new InlineSource('hook.sh', 'payload'),
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

        (new ExecutableSource(
            new InlineSource('hook.sh', 'payload'),
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

        (new ExecutableSource(
            new InlineSource('hook.sh', 'payload'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            ['hook.sh'],
            $reaction->events(),
            'Expected executableWasSet to be emitted',
        );
    }
}
