<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Skill;

use Haspadar\Piqule\Skill\SkillFile;
use Haspadar\Piqule\Tests\Fake\Skill\FakeAgentTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SkillFileTest extends TestCase
{
    #[Test]
    public function nameMatchesTargetPath(): void
    {
        self::assertSame(
            'HARBOR.md',
            (new SkillFile(new FakeAgentTarget('harbor', 'HARBOR.md'), 'body'))->name(),
            'SkillFile name must match the target file path',
        );
    }

    #[Test]
    public function contentsMatchTargetWrapping(): void
    {
        self::assertSame(
            "<!-- fake -->\nbody\n<!-- fake -->",
            (new SkillFile(new FakeAgentTarget('harbor'), 'body'))->contents(),
            'SkillFile contents must equal the target-wrapped body',
        );
    }

    #[Test]
    public function defaultModeIsRegularFile(): void
    {
        self::assertSame(
            0o644,
            (new SkillFile(new FakeAgentTarget('harbor'), 'body'))->mode(),
            'skill files must be written with regular 0644 permissions',
        );
    }

    #[Test]
    public function patternMatchesWrappedContents(): void
    {
        $file = new SkillFile(new FakeAgentTarget('harbor'), 'body');

        self::assertSame(
            1,
            preg_match($file->pattern(), $file->contents()),
            'SkillFile pattern must match the contents produced by the same file',
        );
    }
}
