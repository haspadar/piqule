<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\MarkdownlintSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MarkdownlintSectionTest extends TestCase
{
    #[Test]
    public function convertsExcludesToTrailingGlobPatterns(): void
    {
        self::assertSame(
            ['vendor/**', '.git/**'],
            (new MarkdownlintSection(['vendor', '.git']))->toArray()['markdownlint.ignores'],
            'markdownlint.ignores must use trailing /** glob patterns from dirs.exclude',
        );
    }

    #[Test]
    public function enablesMarkdownlintByDefault(): void
    {
        self::assertSame(
            true,
            (new MarkdownlintSection([]))->toArray()['markdownlint.enabled'],
            'markdownlint.enabled must default to true',
        );
    }
}
