<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit;

use Haspadar\Piqule\Cli;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CliTest extends TestCase
{
    #[Test]
    public function returnsCommandWhenPresent(): void
    {
        self::assertSame(
            'init',
            (new Cli(['bin/piqule', 'init']))->command(),
            'Expected command to be read from argv',
        );
    }

    #[Test]
    public function throwsWhenCommandIsMissing(): void
    {
        $this->expectException(PiquleException::class);

        (new Cli(['bin/piqule']))->command();
    }

    #[Test]
    public function detectsDryRunWhenFlagIsPresent(): void
    {
        self::assertTrue(
            (new Cli(['bin/piqule', '--dry-run']))->isDryRun(),
            'Expected --dry-run to be detected',
        );
    }

    #[Test]
    public function doesNotDetectDryRunWhenFlagIsAbsent(): void
    {
        self::assertFalse(
            (new Cli(['bin/piqule']))->isDryRun(),
            'Expected dry-run to be false when flag is absent',
        );
    }
}
