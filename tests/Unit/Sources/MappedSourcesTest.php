<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Sources;

use Haspadar\Piqule\Source\InlineSource;
use Haspadar\Piqule\Source\Source;
use Haspadar\Piqule\Sources\ListedSources;
use Haspadar\Piqule\Sources\MappedSources;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MappedSourcesTest extends TestCase
{
    #[Test]
    public function mapsFilesUsingProvidedClosure(): void
    {
        self::assertSame(
            'mapped.txt',
            iterator_to_array(
                (new MappedSources(
                    new ListedSources([
                        new InlineSource('original.txt', 'data'),
                    ]),
                    static fn(Source $file) => new InlineSource('mapped.txt', $file->contents()),
                ))->all(),
            )[0]->name(),
            'MappedFiles must apply mapping closure to each File',
        );
    }
}
