<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Check;

use Haspadar\Piqule\Check\SequentialRun;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Fake\Check\FakeCheck;
use Haspadar\Piqule\Tests\Fake\Check\FakeChecks;
use Haspadar\Piqule\Tests\Fake\Check\FakeCliOption;
use Haspadar\Piqule\Tests\Fake\Output\FakeOutput;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SequentialRunTest extends TestCase
{
    #[Test]
    public function passesWhenAllChecksSucceed(): void
    {
        $folder = (new TempFolder())
            ->withFile('a.sh', 'true')
            ->withFile('b.sh', 'true');
        $output = new FakeOutput();

        try {
            (new SequentialRun(
                new FakeChecks([
                    new FakeCheck('alpha', $folder->path() . '/a.sh'),
                    new FakeCheck('beta', $folder->path() . '/b.sh'),
                ]),
                $output,
                new FakeCliOption(false),
            ))->run();

            self::assertNotEmpty(
                $output->successes(),
                'SequentialRun must report success when all checks pass',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function throwsOnFirstFailure(): void
    {
        $folder = (new TempFolder())
            ->withFile('a.sh', 'true')
            ->withFile('b.sh', 'exit 1')
            ->withFile('c.sh', 'true');
        $output = new FakeOutput();

        try {
            $this->expectException(PiquleException::class);

            (new SequentialRun(
                new FakeChecks([
                    new FakeCheck('alpha', $folder->path() . '/a.sh'),
                    new FakeCheck('beta', $folder->path() . '/b.sh'),
                    new FakeCheck('gamma', $folder->path() . '/c.sh'),
                ]),
                $output,
                new FakeCliOption(false),
            ))->run();
        } finally {
            $folder->close();
        }
    }
}
