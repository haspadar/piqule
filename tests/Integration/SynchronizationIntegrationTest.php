<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration;

use Haspadar\Piqule\Source\DiskSources;
use Haspadar\Piqule\Target\Command\Synchronization;
use Haspadar\Piqule\Target\Storage\DiskTargetStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SynchronizationIntegrationTest extends TestCase
{
    #[Test]
    public function synchronizesTemplatesIntoTargetDirectory(): void
    {
        $sources = (new DirectoryFixture('piqule-src'))
            ->withFile('example.txt', 'hello');

        $targets = new DirectoryFixture('piqule-target');

        (new Synchronization(
            new DiskSources($sources->path()),
            new DiskTargetStorage($targets->path()),
            new FakeOutput(),
        ))->run();

        self::assertFileExists(
            $targets->path() . '/example.txt',
            'Expected example.txt to be synchronized into target directory',
        );
    }
}
