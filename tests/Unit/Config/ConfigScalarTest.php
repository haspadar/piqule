<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\ConfigScalarValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfigScalarTest extends TestCase
{
    #[Test]
    public function returnsGivenValue(): void
    {
        self::assertSame(
            '80%',
            (new ConfigScalarValue('80%'))->value(),
            'ConfigScalarValue did not return the given value',
        );
    }
}
