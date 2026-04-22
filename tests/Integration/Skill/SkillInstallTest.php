<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Skill;

use Haspadar\Piqule\Skill\ClaudeTarget;
use Haspadar\Piqule\Skill\SkillFile;
use Haspadar\Piqule\Skill\SkillInstall;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Fake\Storage\Reaction\FakeStorageReaction;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SkillInstallTest extends TestCase
{
    #[Test]
    public function createsClaudeFileWhenMissing(): void
    {
        $folder = new TempFolder();

        (new SkillInstall(
            [new SkillFile(new ClaudeTarget(), 'hello body')],
            new DiskStorage($folder->path()),
            new FakeStorageReaction(),
        ))->run();

        self::assertFileExists(
            $folder->path() . '/CLAUDE.md',
            'CLAUDE.md must be created when installing the Claude target into a fresh project',
        );

        $folder->close();
    }

    #[Test]
    public function writesWrappedSectionBodyIntoCreatedClaudeFile(): void
    {
        $folder = new TempFolder();

        (new SkillInstall(
            [new SkillFile(new ClaudeTarget(), 'hello body')],
            new DiskStorage($folder->path()),
            new FakeStorageReaction(),
        ))->run();

        self::assertSame(
            "<!-- piqule:begin -->\nhello body\n<!-- piqule:end -->",
            file_get_contents($folder->path() . '/CLAUDE.md'),
            'newly created CLAUDE.md must contain the body wrapped between piqule markers',
        );

        $folder->close();
    }

    #[Test]
    public function replacesExistingSectionAndKeepsUserContent(): void
    {
        $folder = (new TempFolder())->withFile(
            'CLAUDE.md',
            "# My project\n\n<!-- piqule:begin -->\nold\n<!-- piqule:end -->\n\n## My rules\nkeep me",
        );

        (new SkillInstall(
            [new SkillFile(new ClaudeTarget(), 'fresh')],
            new DiskStorage($folder->path()),
            new FakeStorageReaction(),
        ))->run();

        self::assertSame(
            "# My project\n\n<!-- piqule:begin -->\nfresh\n<!-- piqule:end -->\n\n## My rules\nkeep me",
            file_get_contents($folder->path() . '/CLAUDE.md'),
            'section replacement must preserve user content surrounding the managed markers',
        );

        $folder->close();
    }

    #[Test]
    public function leavesUnmarkedClaudeFileUntouched(): void
    {
        $folder = (new TempFolder())->withFile(
            'CLAUDE.md',
            "# My rules\n\nnothing piqule-related here",
        );

        (new SkillInstall(
            [new SkillFile(new ClaudeTarget(), 'body')],
            new DiskStorage($folder->path()),
            new FakeStorageReaction(),
        ))->run();

        self::assertSame(
            "# My rules\n\nnothing piqule-related here",
            file_get_contents($folder->path() . '/CLAUDE.md'),
            'pre-existing CLAUDE.md without piqule markers must not be overwritten',
        );

        $folder->close();
    }

    #[Test]
    public function reportsSkippedForUnmarkedExistingFile(): void
    {
        $folder = (new TempFolder())->withFile('CLAUDE.md', 'no markers');
        $reaction = new FakeStorageReaction();

        (new SkillInstall(
            [new SkillFile(new ClaudeTarget(), 'body')],
            new DiskStorage($folder->path()),
            $reaction,
        ))->run();

        self::assertSame(
            ['CLAUDE.md'],
            $reaction->skippedPaths(),
            'skipped() must be reported when the target file lacks piqule markers',
        );

        $folder->close();
    }
}
