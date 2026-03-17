<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\ShellcheckSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ShellcheckSectionTest extends TestCase
{
    #[Test]
    public function propagatesExcludesToIgnoreDirs(): void
    {
        self::assertSame(
            ['vendor', '.git'],
            (new ShellcheckSection(['vendor', '.git']))->toArray()['shellcheck.ignore_dirs'],
            'shellcheck.ignore_dirs must reflect dirs.exclude',
        );
    }
}
