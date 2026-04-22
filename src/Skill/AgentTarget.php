<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Skill;

/**
 * An installation target for an AI coding assistant.
 */
interface AgentTarget
{
    /**
     * Project-relative path of the file that hosts the skill section.
     */
    public function path(): string;

    /**
     * Wraps the rendered skill body with agent-specific section markers.
     */
    public function wrapped(string $body): string;

    /**
     * Regex pattern matching an existing skill section for this target (including markers).
     *
     * @return non-empty-string
     */
    public function markerPattern(): string;
}
