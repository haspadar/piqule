<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Placeholder;

use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultPlaceholderTest extends TestCase
{
    #[Test]
    public function returnsDefaultForPlaceholder(): void
    {
        self::assertSame(
            '80...100',
            (new DefaultPlaceholder('{{ COVERAGE_RANGE }}', '80...100'))->replacement(),
            'DefaultPlaceholder did not return default value',
        );
    }

    #[Test]
    public function returnsExpression(): void
    {
        self::assertSame(
            '{{ EXPRESSION }}',
            (new DefaultPlaceholder('{{ EXPRESSION }}', '75...95'))->expression(),
            'DefaultPlaceholder did not return expression',
        );
    }
}
