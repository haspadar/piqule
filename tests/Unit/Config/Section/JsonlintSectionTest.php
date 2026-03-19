<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\JsonlintSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JsonlintSectionTest extends TestCase
{
    #[Test]
    public function includesNegatedGlobPatternsFromExcludes(): void
    {
        self::assertSame(
            ['**/*.json', '**/*.json5', '**/*.jsonc', '!vendor/**', '!.git/**'],
            (new JsonlintSection(['vendor', '.git']))->toArray()['jsonlint.patterns'],
            'jsonlint.patterns must include negated glob patterns from dirs.exclude',
        );
    }

    #[Test]
    public function enablesCompactOutputByDefault(): void
    {
        self::assertSame(
            true,
            (new JsonlintSection([]))->toArray()['jsonlint.compact'],
            'jsonlint.compact must default to true',
        );
    }

    #[Test]
    public function enablesContinueOnErrorByDefault(): void
    {
        self::assertSame(
            true,
            (new JsonlintSection([]))->toArray()['jsonlint.continue'],
            'jsonlint.continue must default to true',
        );
    }

    #[Test]
    public function disablesDuplicateKeyCheckByDefault(): void
    {
        self::assertSame(
            false,
            (new JsonlintSection([]))->toArray()['jsonlint.duplicate_keys'],
            'jsonlint.duplicate_keys must default to false',
        );
    }

    #[Test]
    public function setsModeToJson5(): void
    {
        self::assertSame(
            ['json5'],
            (new JsonlintSection([]))->toArray()['jsonlint.mode'],
            'jsonlint.mode must default to json5',
        );
    }

    #[Test]
    public function enablesJsonlintByDefault(): void
    {
        self::assertSame(
            true,
            (new JsonlintSection([]))->toArray()['jsonlint.enabled'],
            'jsonlint.enabled must default to true',
        );
    }
}
