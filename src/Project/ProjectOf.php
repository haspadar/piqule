<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\PiquleSentinel;

/**
 * Represents a project determined by the presence of Piqule
 *
 * This object does not decide what to do
 * It delegates behavior to one of the provided projects
 * based on the sentinel state
 */
final class ProjectOf implements Project
{
    private readonly Project $origin;

    public function __construct(
        PiquleSentinel $sentinel,
        Project $initialized,
        Project $uninitialized,
    ) {
        $this->origin = $sentinel->exists()
            ? $initialized
            : $uninitialized;
    }

    public function init(): void
    {
        $this->origin->init();
    }

    public function update(): void
    {
        $this->origin->update();
    }
}
