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

    #[Test]
    public function setsAllowUnsupportedToTrue(): void
    {
        self::assertSame(
            ['true'],
            (new PhpCsFixerSection([]))->toArray()['php_cs_fixer.allow_unsupported'],
            'php_cs_fixer.allow_unsupported must default to true',
        );
    }

    #[Test]
    public function setsPathsToProjectRoot(): void
    {
        self::assertSame(
            ['../..'],
            (new PhpCsFixerSection([]))->toArray()['php_cs_fixer.paths'],
            'php_cs_fixer.paths must default to project root',
        );
    }

    #[Test]
    public function enablesPhpCsFixerByDefault(): void
    {
        self::assertSame(
            true,
            (new PhpCsFixerSection([]))->toArray()['php-cs-fixer.enabled'],
            'php-cs-fixer.enabled must default to true',
        );
    }
}
