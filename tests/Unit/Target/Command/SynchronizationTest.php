<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Target\Command;

use Haspadar\Piqule\Source\Source;
use Haspadar\Piqule\Target\Sync\ReplaceSync;
use Haspadar\Piqule\Tests\Unit\Fake\File\FakeFile;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use Haspadar\Piqule\Tests\Unit\Fake\Source\FakeSources;
use Haspadar\Piqule\Tests\Unit\Fake\Target\Storage\FakeTargetStorage;
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
        $storage = new FakeTargetStorage();
        (new ReplaceSync($sources, $storage, new FakeOutput()))->apply();

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
        $storage = new FakeTargetStorage();
        $storage->write('example.txt', new FakeFile('old'));

        (new ReplaceSync($sources, $storage, new FakeOutput()))->apply();

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
        $storage = new FakeTargetStorage();
        $storage->write('example.txt', new FakeFile('same'));

        (new ReplaceSync($sources, $storage, new FakeOutput()))->apply();

        self::assertSame(
            'same',
            $storage->all()['example.txt']->contents(),
            'Target contents must remain unchanged when contents are equal',
        );
    }
}
