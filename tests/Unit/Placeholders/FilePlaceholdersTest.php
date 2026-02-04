<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Placeholders;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Haspadar\Piqule\Placeholders\FilePlaceholders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FilePlaceholdersTest extends TestCase
{
    #[Test]
    public function extractsDefaultPlaceholderFromFile(): void
    {
        $placeholders = new FilePlaceholders(
            new TextFile(
                'config.yml',
                'coverage: {{ COVERAGE_RANGE | default("80...100") }}',
            ),
        );

        self::assertEquals(
            [new DefaultPlaceholder('{{ COVERAGE_RANGE | default("80...100") }}', '80...100')],
            [...$placeholders->all()],
            'FilePlaceholders did not extract default placeholder from file',
        );
    }
}
