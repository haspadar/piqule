<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Target\Storage;

use Haspadar\Piqule\Target\Storage\DryRunTargetStorage;
use Haspadar\Piqule\Tests\Fake\File\FakeFile;
use Haspadar\Piqule\Tests\Fake\Target\Storage\FakeTargetStorage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DryRunTargetStorageTest extends TestCase
{
    #[Test]
    public function doesNotPersistFilesOnWrite(): void
    {
        $origin = new FakeTargetStorage();
        $storage = new DryRunTargetStorage($origin);

        $storage->write('file.txt', new FakeFile('content'));

        self::assertSame(
            [],
            $origin->all(),
            'DryRunTargetStorage must not persist files to origin storage',
        );
    }

    #[Test]
    public function delegatesExistsToOrigin(): void
    {
        $origin = new FakeTargetStorage();
        $origin->write('file.txt', new FakeFile('content'));

        $storage = new DryRunTargetStorage($origin);

        self::assertTrue(
            $storage->exists('file.txt'),
            'DryRunTargetStorage must delegate exists() to origin storage',
        );
    }

    #[Test]
    public function delegatesReadToOrigin(): void
    {
        $origin = new FakeTargetStorage();
        $origin->write('file.txt', new FakeFile('content'));

        $storage = new DryRunTargetStorage($origin);

        self::assertSame(
            'content',
            $storage->read('file.txt')->contents(),
            'DryRunTargetStorage must delegate read() to origin storage',
        );
    }
}
