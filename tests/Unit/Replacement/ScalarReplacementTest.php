<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Replacement;

use Haspadar\Piqule\Replacement\ScalarReplacement;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ScalarReplacementTest extends TestCase
{
    #[Test]
    public function returnsGivenValue(): void
    {
        self::assertSame(
            '80%',
            (new ScalarReplacement('80%'))->value(),
            'ScalarReplacement did not return the given value',
        );
    }
}
