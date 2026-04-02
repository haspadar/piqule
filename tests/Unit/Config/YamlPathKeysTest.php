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

        new YamlPathKeys(['php.src' => [42]], [], new DefaultConfig());
    }

    #[Test]
    public function throwsWhenOverrideExcludeContainsNonString(): void
    {
        $this->expectException(PiquleException::class);

        new YamlPathKeys(['exclude' => [true]], [], new DefaultConfig());
    }

    #[Test]
    public function throwsWhenAppendExcludeContainsNonString(): void
    {
        $this->expectException(PiquleException::class);

        new YamlPathKeys([], ['exclude' => [null]], new DefaultConfig());
    }

    #[Test]
    public function throwsWhenAppendPhpSrcContainsNonString(): void
    {
        $this->expectException(PiquleException::class);

        new YamlPathKeys([], ['php.src' => [3.14]], new DefaultConfig());
    }
}
