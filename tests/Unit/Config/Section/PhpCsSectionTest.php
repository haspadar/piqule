<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpCsSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpCsSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToFiles(): void
    {
        self::assertSame(
            ['../../src'],
            (new PhpCsSection(['../../src'], ['vendor/*']))->toArray()['phpcs.files'],
            'phpcs.files must reflect the given includes',
        );
    }

    #[Test]
    public function propagatesExcludesToExcludes(): void
    {
        self::assertSame(
            ['vendor/*'],
            (new PhpCsSection(['../../src'], ['vendor/*']))->toArray()['phpcs.excludes'],
            'phpcs.excludes must reflect the given excludes',
        );
    }

    #[Test]
    public function enablesPhpCsByDefault(): void
    {
        self::assertSame(
            true,
            (new PhpCsSection([], []))->toArray()['phpcs.enabled'],
            'phpcs.enabled must default to true',
        );
    }

    #[Test]
    public function exposesRootNamespace(): void
    {
        self::assertSame(
            'Acme\\App',
            (new PhpCsSection([], [], 'Acme\\App'))->toArray()['phpcs.root_namespace'],
            'phpcs.root_namespace must reflect the given root namespace',
        );
    }
}
