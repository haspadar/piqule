<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\ConfigMissingValue;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfigMissingTest extends TestCase
{
    #[Test]
    public function throwsExceptionWhenValueIsRequested(): void
    {
        $this->expectException(PiquleException::class);

        (new ConfigMissingValue())->value();
    }
}
