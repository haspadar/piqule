<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\InfectionSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InfectionSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToSourceDirectories(): void
    {
        self::assertSame(
            ['../../src'],
            (new InfectionSection(['../../src']))->toArray()['infection.source.directories'],
            'infection.source.directories must reflect the given includes',
        );
    }

    #[Test]
    public function setsPhpOptionsToMemoryLimit(): void
    {
        self::assertSame(
            '-d memory_limit=1G',
            (new InfectionSection([]))->toArray()['infection.php_options'],
            'infection.php_options must default to 1G memory limit',
        );
    }

    #[Test]
    public function setsTimeoutTo30(): void
    {
        self::assertSame(
            '30',
            (new InfectionSection([]))->toArray()['infection.timeout'],
            'infection.timeout must default to 30',
        );
    }

    #[Test]
    public function enablesInfectionByDefault(): void
    {
        self::assertSame(
            true,
            (new InfectionSection([]))->toArray()['infection.enabled'],
            'infection.enabled must default to true',
        );
    }
}
