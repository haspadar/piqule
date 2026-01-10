<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration;

use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\Storage\DiskTargetStorage;
use Haspadar\Piqule\Target\Sync\ReplaceSync;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SyncIntegrationTest extends TestCase
{
    #[Test]
    public function synchronizesTemplatesIntoTargetDirectory(): void
    {
        $sources = (new DirectoryFixture('piqule-src'))
            ->withFile('example.txt', 'hello');

        $targets = new DirectoryFixture('piqule-target');

        (new ReplaceSync(
            new DiskSources($sources->path()),
            new DiskTargetStorage($targets->path()),
            new FakeOutput(),
        ))->apply();

        self::assertFileExists(
            $targets->path() . '/example.txt',
            'Expected example.txt to be synchronized into target directory',
        );
    }
}
