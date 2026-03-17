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
}
