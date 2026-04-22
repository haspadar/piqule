<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Skill;

use Haspadar\Piqule\Skill\ClaudeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ClaudeTargetTest extends TestCase
{
    #[Test]
    public function installsIntoProjectLevelClaudeMd(): void
    {
        self::assertSame(
            'CLAUDE.md',
            (new ClaudeTarget())->path(),
            'Claude Code reads the project-level CLAUDE.md file',
        );
    }

    #[Test]
    public function wrapsBodyBetweenBeginAndEndMarkers(): void
    {
        self::assertSame(
            "<!-- piqule:begin -->\nhello\n<!-- piqule:end -->",
            (new ClaudeTarget())->wrapped('hello'),
            'body must sit between piqule:begin and piqule:end markers',
        );
    }

    #[Test]
    public function trimsTrailingNewlinesBeforeEndMarker(): void
    {
        self::assertSame(
            "<!-- piqule:begin -->\nhello\n<!-- piqule:end -->",
            (new ClaudeTarget())->wrapped("hello\n\n\n"),
            'trailing blank lines must not push the end marker down',
        );
    }

    #[Test]
    public function markerPatternMatchesWrappedOutput(): void
    {
        self::assertSame(
            1,
            preg_match(
                (new ClaudeTarget())->markerPattern(),
                (new ClaudeTarget())->wrapped('body'),
            ),
            'marker pattern must match the exact output produced by wrapped()',
        );
    }

    #[Test]
    public function markerPatternSpansMultilineBodies(): void
    {
        self::assertSame(
            1,
            preg_match(
                (new ClaudeTarget())->markerPattern(),
                (new ClaudeTarget())->wrapped("line1\nline2\nline3"),
            ),
            'marker pattern must match section bodies that span multiple lines',
        );
    }

    #[Test]
    public function markerPatternDoesNotMatchAbsentMarkers(): void
    {
        self::assertSame(
            0,
            preg_match(
                (new ClaudeTarget())->markerPattern(),
                "# Project\n\nUnrelated content without markers.",
            ),
            'marker pattern must not match text that lacks the markers',
        );
    }
}
