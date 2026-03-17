<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpCsFixerSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpCsFixerSectionTest extends TestCase
{
    #[Test]
    public function propagatesExcludesToFixerExclude(): void
    {
        self::assertSame(
            ['vendor', '.git'],
            (new PhpCsFixerSection(['vendor', '.git']))->toArray()['php_cs_fixer.exclude'],
            'php_cs_fixer.exclude must reflect dirs.exclude',
        );
    }
}
