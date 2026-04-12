<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\YamlPathKeys;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class YamlPathKeysTest extends TestCase
{
    #[Test]
    public function throwsWhenOverridePhpSrcContainsNonString(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessageMatches('/override\.php\.src/');

        $keys = new YamlPathKeys(['php.src' => [42]], [], new DefaultConfig());
        $keys->phpSrc();
    }

    #[Test]
    public function throwsWhenOverrideExcludeContainsNonString(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessageMatches('/override\.exclude/');

        $keys = new YamlPathKeys(['exclude' => [true]], [], new DefaultConfig());
        $keys->exclude();
    }

    #[Test]
    public function throwsWhenAppendExcludeContainsNonString(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessageMatches('/append\.exclude/');

        $keys = new YamlPathKeys([], ['exclude' => [null]], new DefaultConfig());
        $keys->exclude();
    }

    #[Test]
    public function throwsWhenAppendPhpSrcContainsNonString(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessageMatches('/append\.php\.src/');

        $keys = new YamlPathKeys([], ['php.src' => [3.14]], new DefaultConfig());
        $keys->phpSrc();
    }

    #[Test]
    public function deduplicatesAppendedPhpSrcEntries(): void
    {
        $keys = new YamlPathKeys(
            ['php.src' => ['src']],
            ['php.src' => ['src', 'lib']],
            new DefaultConfig(),
        );

        self::assertSame(
            ['src', 'lib'],
            $keys->phpSrc(),
            'Appended php.src entries must be deduplicated',
        );
    }

    #[Test]
    public function deduplicatesAppendedExcludeEntries(): void
    {
        $keys = new YamlPathKeys(
            ['exclude' => ['vendor']],
            ['exclude' => ['vendor', 'tests']],
            new DefaultConfig(),
        );

        self::assertSame(
            ['vendor', 'tests'],
            $keys->exclude(),
            'Appended exclude entries must be deduplicated',
        );
    }

    #[Test]
    public function normalizesAssociativeOverridePhpSrcKeys(): void
    {
        $keys = new YamlPathKeys(
            ['php.src' => ['a' => 'src', 'b' => 'lib']],
            [],
            new DefaultConfig(),
        );

        self::assertSame(
            ['src', 'lib'],
            $keys->phpSrc(),
            'Associative php.src overrides must be normalized to a sequential list',
        );
    }

    #[Test]
    public function normalizesAssociativeOverrideExcludeKeys(): void
    {
        $keys = new YamlPathKeys(
            ['exclude' => ['x' => 'vendor', 'y' => 'tests']],
            [],
            new DefaultConfig(),
        );

        self::assertSame(
            ['vendor', 'tests'],
            $keys->exclude(),
            'Associative exclude overrides must be normalized to a sequential list',
        );
    }

    #[Test]
    public function normalizesAssociativeAppendPhpSrcKeys(): void
    {
        $keys = new YamlPathKeys(
            ['php.src' => ['src']],
            ['php.src' => ['a' => 'lib']],
            new DefaultConfig(),
        );

        self::assertSame(
            ['src', 'lib'],
            $keys->phpSrc(),
            'Associative append php.src must be normalized before merging',
        );
    }

    #[Test]
    public function normalizesAssociativeAppendExcludeKeys(): void
    {
        $keys = new YamlPathKeys(
            ['exclude' => ['vendor']],
            ['exclude' => ['a' => 'tests']],
            new DefaultConfig(),
        );

        self::assertSame(
            ['vendor', 'tests'],
            $keys->exclude(),
            'Associative append exclude must be normalized before merging',
        );
    }
}
