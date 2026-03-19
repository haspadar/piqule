<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpMdSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpMdSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToPaths(): void
    {
        self::assertSame(
            ['src'],
            (new PhpMdSection(['src']))->toArray()['phpmd.paths'],
            'phpmd.paths must reflect the given includes',
        );
    }

    #[Test]
    public function setsClassComplexityThresholdTo50(): void
    {
        self::assertSame(
            [50],
            (new PhpMdSection([]))->toArray()['phpmd.class_complexity'],
            'phpmd.class_complexity must default to 50',
        );
    }

    #[Test]
    public function setsClassLengthThresholdTo200(): void
    {
        self::assertSame(
            [200],
            (new PhpMdSection([]))->toArray()['phpmd.class_length'],
            'phpmd.class_length must default to 200',
        );
    }

    #[Test]
    public function setsCyclomaticComplexityTo10(): void
    {
        self::assertSame(
            [10],
            (new PhpMdSection([]))->toArray()['phpmd.cyclomatic'],
            'phpmd.cyclomatic must default to 10',
        );
    }

    #[Test]
    public function setsMaxFieldsTo10(): void
    {
        self::assertSame(
            [10],
            (new PhpMdSection([]))->toArray()['phpmd.max_fields'],
            'phpmd.max_fields must default to 10',
        );
    }

    #[Test]
    public function setsMaxMethodsTo10(): void
    {
        self::assertSame(
            [10],
            (new PhpMdSection([]))->toArray()['phpmd.max_methods'],
            'phpmd.max_methods must default to 10',
        );
    }

    #[Test]
    public function setsMaxParametersTo5(): void
    {
        self::assertSame(
            [5],
            (new PhpMdSection([]))->toArray()['phpmd.max_parameters'],
            'phpmd.max_parameters must default to 5',
        );
    }

    #[Test]
    public function setsMethodLengthThresholdTo50(): void
    {
        self::assertSame(
            [50],
            (new PhpMdSection([]))->toArray()['phpmd.method_length'],
            'phpmd.method_length must default to 50',
        );
    }

    #[Test]
    public function setsNpathComplexityTo200(): void
    {
        self::assertSame(
            [200],
            (new PhpMdSection([]))->toArray()['phpmd.npath'],
            'phpmd.npath must default to 200',
        );
    }

    #[Test]
    public function enablesPhpMdByDefault(): void
    {
        self::assertSame(
            true,
            (new PhpMdSection([]))->toArray()['phpmd.enabled'],
            'phpmd.enabled must default to true',
        );
    }
}
