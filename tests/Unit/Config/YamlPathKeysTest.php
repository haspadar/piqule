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
}
