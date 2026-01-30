<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\ExecutableFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use Haspadar\Piqule\Tests\Unit\Fake\Storage\AlwaysExecutableStorage;
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
    public function writesFileToStorage(): void
    {
        $storage = new InMemoryStorage();

        (new ExecutableFile(
            new InlineFile('hook.sh', 'payload'),
        ))->writeTo($storage, new FakeFileReaction());

        self::assertSame(
            'payload',
            $storage->read('hook.sh'),
        );
    }

    #[Test]
    public function emitsExecutableAlreadySetWhenStorageReportsExecutable(): void
    {
        $storage = new AlwaysExecutableStorage(
            new InMemoryStorage([
                'hook.sh' => 'payload',
            ]),
        );

        $reaction = new FakeFileReaction();

        (new ExecutableFile(
            new InlineFile('hook.sh', 'payload'),
        ))->writeTo($storage, $reaction);

        self::assertSame(
            ['hook.sh'],
            $reaction->events(),
            'Expected executableAlreadySet to be emitted',
        );
    }
}
