<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\ConfigListValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfigListTest extends TestCase
{
    #[Test]
    public function returnsGivenValues(): void
    {
        self::assertSame(
            ['a', 'b'],
            (new ConfigListValue(['a', 'b']))->value(),
            'ConfigListValue did not return given values',
        );
    }
}
