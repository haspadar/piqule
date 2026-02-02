<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source;

use Haspadar\Piqule\Source\InlineSource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InlineSourceTest extends TestCase
{
    #[Test]
    public function returnsProvidedName(): void
    {
        $file = new InlineSource('example.txt', 'hello');

        self::assertSame(
            'example.txt',
            $file->name(),
        );
    }

    #[Test]
    public function returnsProvidedContents(): void
    {
        $file = new InlineSource('example.txt', 'hello');

        self::assertSame(
            'hello',
            $file->contents(),
        );
    }
}
