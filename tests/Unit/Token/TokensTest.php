<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Token;

use Haspadar\Piqule\Token\CodecovToken;
use Haspadar\Piqule\Token\InfectionToken;
use Haspadar\Piqule\Token\Tokens;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TokensTest extends TestCase
{
    #[Test]
    public function returnsAllItems(): void
    {
        $codecov = new CodecovToken();
        $infection = new InfectionToken();

        self::assertSame(
            [$codecov, $infection],
            (new Tokens([$codecov, $infection]))->items(),
            'Tokens must return all items in order',
        );
    }

    #[Test]
    public function returnsEmptyListWhenNoItems(): void
    {
        self::assertSame(
            [],
            (new Tokens([]))->items(),
            'Tokens must return empty list when constructed with no items',
        );
    }
}
