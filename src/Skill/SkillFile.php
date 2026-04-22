<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Skill;

use Haspadar\Piqule\File\File;
use Override;

/**
 * A File that renders the wrapped skill body for a given agent target.
 */
final readonly class SkillFile implements File
{
    private const int DEFAULT_MODE = 0o644;

    /** Initializes with the agent target and the raw skill body to wrap. */
    public function __construct(private AgentTarget $target, private string $body) {}

    #[Override]
    public function name(): string
    {
        return $this->target->path();
    }

    #[Override]
    public function contents(): string
    {
        return $this->target->wrapped($this->body);
    }

    #[Override]
    public function mode(): int
    {
        return self::DEFAULT_MODE;
    }

    /**
     * Returns the PCRE pattern that matches this file's managed section.
     *
     * @return non-empty-string
     */
    public function pattern(): string
    {
        return $this->target->markerPattern();
    }
}
