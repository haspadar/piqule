<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Check;

use Haspadar\Piqule\Check\ParallelRun;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Fake\Check\FakeCheck;
use Haspadar\Piqule\Tests\Fake\Check\FakeChecks;
use Haspadar\Piqule\Tests\Fake\Check\FakeCliOption;
use Haspadar\Piqule\Tests\Fake\Output\FakeOutput;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ParallelRunTest extends TestCase
{
    #[Test]
    public function passesWhenAllChecksSucceedInParallel(): void
    {
        $folder = (new TempFolder())
            ->withFile('a.sh', 'true')
            ->withFile('b.sh', 'true');
        $output = new FakeOutput();

        try {
            (new ParallelRun(
                new FakeChecks([
                    new FakeCheck('alpha', $folder->path() . '/a.sh'),
                    new FakeCheck('beta', $folder->path() . '/b.sh'),
                ]),
                $output,
                new FakeCliOption(false),
            ))->run();

            self::assertNotEmpty(
                $output->successes(),
                'ParallelRun must report success when all checks pass',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function throwsWhenAnyCheckFails(): void
    {
        $folder = (new TempFolder())
            ->withFile('a.sh', 'true')
            ->withFile('b.sh', 'exit 1');
        $output = new FakeOutput();

        try {
            $this->expectException(PiquleException::class);

            (new ParallelRun(
                new FakeChecks([
                    new FakeCheck('alpha', $folder->path() . '/a.sh'),
                    new FakeCheck('beta', $folder->path() . '/b.sh'),
                ]),
                $output,
                new FakeCliOption(false),
            ))->run();
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function runsDependentChecksAfterIndependent(): void
    {
        $folder = (new TempFolder())
            ->withFile('phpunit.sh', 'true')
            ->withFile('infection.sh', 'true');
        $output = new FakeOutput();

        try {
            (new ParallelRun(
                new FakeChecks([
                    new FakeCheck('infection', $folder->path() . '/infection.sh'),
                    new FakeCheck('phpunit', $folder->path() . '/phpunit.sh'),
                ]),
                $output,
                new FakeCliOption(false),
            ))->run();

            self::assertNotEmpty(
                $output->successes(),
                'ParallelRun must run dependent checks (infection) after independent (phpunit)',
            );
        } finally {
            $folder->close();
        }
    }
}
