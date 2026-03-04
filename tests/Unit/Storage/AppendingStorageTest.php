<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\AppendingStorage;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Constraint\Storage\ReactionWasSilent;
use Haspadar\Piqule\Tests\Fake\Storage\Reaction\FakeStorageReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AppendingStorageTest extends TestCase
{
    #[Test]
    public function createsFileWhenItDoesNotExist(): void
    {
        $reaction = new FakeStorageReaction();

        (new AppendingStorage(
            new InMemoryStorage(),
            $reaction,
            '# BEGIN piqule',
        ))->write(new TextFile('pre-push', "#!/usr/bin/env sh\n# BEGIN piqule\n# END piqule"));

        self::assertSame(
            ['pre-push'],
            $reaction->createdPaths(),
            'created() must be called when file does not exist',
        );
    }

    #[Test]
    public function appendsContentWhenMarkerIsAbsent(): void
    {
        $reaction = new FakeStorageReaction();
        $storage = new AppendingStorage(
            new InMemoryStorage([
                'pre-push' => new TextFile('pre-push', '#!/usr/bin/env sh'),
            ]),
            $reaction,
            '# BEGIN piqule',
        );

        $result = $storage->write(new TextFile('pre-push', "# BEGIN piqule\n# END piqule"));

        self::assertSame(
            ['pre-push'],
            $reaction->updatedPaths(),
            'updated() must be called when marker is absent',
        );
        self::assertStringContainsString(
            '# BEGIN piqule',
            $result->read('pre-push'),
            'read() must return merged content after append',
        );
    }

    #[Test]
    public function skipsWriteWhenMarkerAlreadyPresent(): void
    {
        $reaction = new FakeStorageReaction();

        (new AppendingStorage(
            new InMemoryStorage([
                'pre-push' => new TextFile('pre-push', "#!/usr/bin/env sh\n# BEGIN piqule\n# END piqule"),
            ]),
            $reaction,
            '# BEGIN piqule',
        ))->write(new TextFile('pre-push', "# BEGIN piqule\n# END piqule"));

        self::assertThat(
            $reaction,
            new ReactionWasSilent(),
            'no reaction must be triggered when marker is already present',
        );
    }

    #[Test]
    public function returnsSameInstanceWhenMarkerAlreadyPresent(): void
    {
        $storage = new AppendingStorage(
            new InMemoryStorage([
                'pre-push' => new TextFile('pre-push', "#!/usr/bin/env sh\n# BEGIN piqule\n# END piqule"),
            ]),
            new FakeStorageReaction(),
            '# BEGIN piqule',
        );

        self::assertSame(
            $storage,
            $storage->write(new TextFile('pre-push', "# BEGIN piqule\n# END piqule")),
            'write() must return the same instance when marker is already present',
        );
    }

    #[Test]
    public function returnsNewInstanceAfterCreation(): void
    {
        $storage = new AppendingStorage(
            new InMemoryStorage(),
            new FakeStorageReaction(),
            '# BEGIN piqule',
        );

        $result = $storage->write(new TextFile('pre-push', "#!/usr/bin/env sh\n# BEGIN piqule\n# END piqule"));

        self::assertNotSame(
            $storage,
            $result,
            'write() must return a new instance to preserve immutability',
        );
    }

    #[Test]
    public function readsFileContentFromOrigin(): void
    {
        self::assertSame(
            '#!/usr/bin/env sh',
            (new AppendingStorage(
                new InMemoryStorage(['pre-push' => new TextFile('pre-push', '#!/usr/bin/env sh')]),
                new FakeStorageReaction(),
                '# BEGIN piqule',
            ))->read('pre-push'),
            'read() must delegate to origin storage',
        );
    }

    #[Test]
    public function returnsModeFromOrigin(): void
    {
        self::assertSame(
            0o755,
            (new AppendingStorage(
                new InMemoryStorage(['pre-push' => new TextFile('pre-push', '', 0o755)]),
                new FakeStorageReaction(),
                '# BEGIN piqule',
            ))->mode('pre-push'),
            'mode() must delegate to origin storage',
        );
    }
}
