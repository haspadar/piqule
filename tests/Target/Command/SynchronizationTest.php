<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Target\Command;

use Haspadar\Piqule\Source\Source;
use Haspadar\Piqule\Target\Command\Synchronization;
use Haspadar\Piqule\Tests\Fake\File\FakeFile;
use Haspadar\Piqule\Tests\Fake\Output\FakeOutput;
use Haspadar\Piqule\Tests\Fake\Source\FakeSources;
use Haspadar\Piqule\Tests\Fake\Target\Storage\InMemoryTargetStorage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SynchronizationTest extends TestCase
{
    #[Test]
    public function createsFileWhenTargetDoesNotExist(): void
    {
        $sources = new FakeSources([
            new Source(
                new FakeFile('hello'),
                'example.txt',
            ),
        ]);
        $storage = new InMemoryTargetStorage();
        (new Synchronization($sources, $storage, new FakeOutput()))->run();

        self::assertArrayHasKey(
            'example.txt',
            $storage->all(),
            'Target must be materialized when it does not exist',
        );
    }

    #[Test]
    public function updatesFileWhenContentsDiffer(): void
    {
        $sources = new FakeSources([
            new Source(
                new FakeFile('new'),
                'example.txt',
            ),
        ]);
        $storage = new InMemoryTargetStorage();
        $storage->write('example.txt', new FakeFile('old'));

        (new Synchronization($sources, $storage, new FakeOutput()))->run();

        self::assertSame(
            'new',
            $storage->all()['example.txt']->contents(),
            'Target contents must be updated when source contents differ',
        );
    }

    #[Test]
    public function skipsFileWhenContentsAreEqual(): void
    {
        $sources = new FakeSources([
            new Source(
                new FakeFile('same'),
                'example.txt',
            ),
        ]);
        $storage = new InMemoryTargetStorage();
        $storage->write('example.txt', new FakeFile('same'));

        (new Synchronization($sources, $storage, new FakeOutput()))->run();

        self::assertSame(
            'same',
            $storage->all()['example.txt']->contents(),
            'Target contents must remain unchanged when contents are equal',
        );
    }
}
