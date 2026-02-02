<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Sources;

use Haspadar\Piqule\Source\InlineSource;
use Haspadar\Piqule\Sources\CompositeSources;
use Haspadar\Piqule\Sources\ListedSources;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CompositeSourcesTest extends TestCase
{
    #[Test]
    public function combinesFilesFromAllSources(): void
    {
        $files = new CompositeSources([
            new ListedSources([
                new InlineSource('a.txt', 'A'),
            ]),
            new ListedSources([
                new InlineSource('b.txt', 'B'),
            ]),
        ]);

        self::assertEquals(
            [
                new InlineSource('a.txt', 'A'),
                new InlineSource('b.txt', 'B'),
            ],
            [...$files->all()],
            'Files were not combined in source order',
        );
    }
}
