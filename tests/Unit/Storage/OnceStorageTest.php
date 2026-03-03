<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Storage\OnceStorage;
use Haspadar\Piqule\Tests\Fake\Storage\Reaction\FakeStorageReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OnceStorageTest extends TestCase
{
    #[Test]
    public function createsFileWhenItDoesNotExist(): void
    {
        $reaction = new FakeStorageReaction();

        (new OnceStorage(
            new InMemoryStorage(),
            $reaction,
        ))->write(new TextFile('bootstrap.php', '<?php'));

        self::assertSame(
            ['bootstrap.php'],
            $reaction->createdPaths(),
            'created() must be called for a new file',
        );
    }

    #[Test]
    public function skipsFileWhenItAlreadyExists(): void
    {
        $reaction = new FakeStorageReaction();

        (new OnceStorage(
            new InMemoryStorage([
                'bootstrap.php' => new TextFile('bootstrap.php', '<?php // original'),
            ]),
            $reaction,
        ))->write(new TextFile('bootstrap.php', '<?php // new'));

        self::assertSame(
            [],
            $reaction->createdPaths(),
            'created() must not be called when file already exists',
        );
        self::assertSame(
            [],
            $reaction->updatedPaths(),
            'updated() must not be called when file already exists',
        );
    }

    #[Test]
    public function returnsNewInstanceAfterCreation(): void
    {
        $storage = new OnceStorage(
            new InMemoryStorage(),
            new FakeStorageReaction(),
        );

        $result = $storage->write(new TextFile('init.env', 'APP_ENV=local'));

        self::assertNotSame(
            $storage,
            $result,
            'write() must return a new instance to preserve immutability',
        );
    }
}
