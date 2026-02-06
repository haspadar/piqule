<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Placeholders;

use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Haspadar\Piqule\Placeholders\CombinedPlaceholders;
use Haspadar\Piqule\Tests\Constraint\Placeholders\HasPlaceholders;
use Haspadar\Piqule\Tests\Fake\Placeholders\FakePlaceholders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CombinedPlaceholdersTest extends TestCase
{
    #[Test]
    public function combinesPlaceholdersFromMultipleSources(): void
    {
        self::assertThat(
            new CombinedPlaceholders([
                new FakePlaceholders([
                    new DefaultPlaceholder('A', '1'),
                ]),
                new FakePlaceholders([
                    new DefaultPlaceholder('B', '2'),
                ]),
            ]),
            new HasPlaceholders([
                'A' => '1',
                'B' => '2',
            ]),
        );
    }

    #[Test]
    public function ignoresEmptyPlaceholders(): void
    {
        self::assertThat(
            new CombinedPlaceholders([
                new FakePlaceholders([]),
                new FakePlaceholders([
                    new DefaultPlaceholder('A', '1'),
                ]),
            ]),
            new HasPlaceholders([
                'A' => '1',
            ]),
        );
    }
}
