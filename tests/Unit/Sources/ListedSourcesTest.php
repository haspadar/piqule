<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Sources;

use Haspadar\Piqule\Source\InlineSource;
use Haspadar\Piqule\Sources\ListedSources;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ListedSourcesTest extends TestCase
{
    #[Test]
    public function returnsFilesInOriginalOrder(): void
    {
        self::assertSame(
            ['a.txt', 'b.txt'],
            array_map(
                static fn($file) => $file->name(),
                iterator_to_array(
                    (new ListedSources([
                        new InlineSource('a.txt', 'A'),
                        new InlineSource('b.txt', 'B'),
                    ]))->all(),
                ),
            ),
            'ListedFiles must return provided files in original order',
        );
    }
}
