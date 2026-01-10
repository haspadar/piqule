<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Target\Sync;

use Haspadar\Piqule\Source\Source;
use Haspadar\Piqule\Target\Sync\SkippingIfExistsSync;
use Haspadar\Piqule\Tests\Unit\Fake\File\FakeFile;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use Haspadar\Piqule\Tests\Unit\Fake\Source\FakeSources;
use Haspadar\Piqule\Tests\Unit\Fake\Target\Storage\FakeTargetStorage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SkippingIfExistsSyncTest extends TestCase
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

        (new SkippingIfExistsSync($sources, $storage, new FakeOutput()))->apply();

        self::assertArrayHasKey(
            'example.txt',
            $storage->all(),
            'Target must be created when it does not exist',
        );
    }

    #[Test]
    public function skipsFileWhenTargetExists(): void
    {
        $sources = new FakeSources([
            new Source(
                new FakeFile('new'),
                'example.txt',
            ),
        ]);
        $storage = new FakeTargetStorage();
        $storage->write('example.txt', new FakeFile('old'));

        (new SkippingIfExistsSync($sources, $storage, new FakeOutput()))->apply();

        self::assertSame(
            'old',
            $storage->all()['example.txt']->contents(),
            'Target contents must remain unchanged when target already exists',
        );
    }

    #[Test]
    public function doesNotCompareContentsWhenTargetExists(): void
    {
        $sources = new FakeSources([
            new Source(
                new FakeFile('different'),
                'example.txt',
            ),
        ]);
        $storage = new FakeTargetStorage();
        $storage->write('example.txt', new FakeFile('original'));

        (new SkippingIfExistsSync($sources, $storage, new FakeOutput()))->apply();

        self::assertSame(
            'original',
            $storage->all()['example.txt']->contents(),
            'Existing target must never be replaced regardless of source contents',
        );
    }
}
