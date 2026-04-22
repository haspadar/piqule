<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Skill;

use Override;

/**
 * Installs the piqule skill into the project-level `CLAUDE.md` file read by Claude Code.
 */
final readonly class ClaudeTarget implements AgentTarget
{
    private const string FILE = 'CLAUDE.md';
    private const string BEGIN = '<!-- piqule:begin -->';
    private const string END = '<!-- piqule:end -->';

    #[Override]
    public function path(): string
    {
        return self::FILE;
    }

    #[Override]
    public function wrapped(string $body): string
    {
        return sprintf("%s\n%s\n%s", self::BEGIN, rtrim($body, "\n"), self::END);
    }

    #[Override]
    public function markerPattern(): string
    {
        return sprintf(
            '/%s.*?%s/s',
            preg_quote(self::BEGIN, '/'),
            preg_quote(self::END, '/'),
        );
    }
}
