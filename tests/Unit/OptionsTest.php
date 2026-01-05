<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit;

use Haspadar\Piqule\Options;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OptionsTest extends TestCase
{
    #[Test]
    public function detectsDryRunWhenFlagIsPresent(): void
    {
        self::assertTrue(
            (new Options(['bin/piqule', '--dry-run']))->isDryRun(),
            'Expected --dry-run to be detected',
        );
    }

    #[Test]
    public function doesNotDetectDryRunWhenFlagIsAbsent(): void
    {
        self::assertFalse(
            (new Options(['bin/piqule']))->isDryRun(),
            'Expected dry-run to be false when flag is absent',
        );
    }
}
