<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Target\Materialization\Materialization;

/**
 * Represents a project determined by the presence of Piqule
 *
 * This object does not decide what to do
 * It delegates behavior to one of the provided projects
 * based on the sentinel state
 */
final readonly class ProjectOf implements Project
{
    private Project $origin;

    public function __construct(
        Sentinel $sentinel,
        Project  $initialized,
        Project  $uninitialized,
    ) {
        $this->origin = $sentinel->exists()
            ? $initialized
            : $uninitialized;
    }

    public function init(Materialization $materialization): void
    {
        $this->origin->init($materialization);
    }

    public function update(Materialization $materialization): void
    {
        $this->origin->update($materialization);
    }
}
