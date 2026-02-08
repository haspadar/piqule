<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Replacement;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Replacement\MissingReplacement;
use Haspadar\Piqule\Replacement\ScalarReplacement;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MissingReplacementTest extends TestCase
{
    #[Test]
    public function returnsDefaultReplacementWhenProvided(): void
    {
        self::assertSame(
            'fallback',
            (new MissingReplacement())
                ->withDefault(new ScalarReplacement('fallback'))
                ->value(),
            'MissingReplacement did not return provided default replacement',
        );
    }

    #[Test]
    public function throwsExceptionWhenValueIsRequested(): void
    {
        $this->expectException(PiquleException::class);

        (new MissingReplacement())->value();
    }
}
