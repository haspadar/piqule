<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpUnitSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpUnitSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToSourceInclude(): void
    {
        self::assertSame(
            ['../../src'],
            (new PhpUnitSection(['../../src']))->toArray()['phpunit.source.include'],
            'phpunit.source.include must reflect the given includes',
        );
    }

    #[Test]
    public function setsIntegrationTestsuitePath(): void
    {
        self::assertSame(
            ['../../tests/Integration'],
            (new PhpUnitSection([]))->toArray()['phpunit.testsuites.integration'],
            'phpunit.testsuites.integration must default to ../../tests/Integration',
        );
    }

    #[Test]
    public function setsPhpOptionsToMemoryLimit(): void
    {
        self::assertSame(
            '-d memory_limit=1G',
            (new PhpUnitSection([]))->toArray()['phpunit.php_options'],
            'phpunit.php_options must default to 1G memory limit',
        );
    }

    #[Test]
    public function enablesPhpUnitByDefault(): void
    {
        self::assertSame(
            true,
            (new PhpUnitSection([]))->toArray()['phpunit.enabled'],
            'phpunit.enabled must default to true',
        );
    }
}
