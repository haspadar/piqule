<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Project\Lock\Lock;
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
    public function __construct(
        private DiskPiquleDirectory $piqule,
        private Project             $initialized,
        private Project             $uninitialized,
    ) {}

    private function project(): Project
    {
        return $this->piqule->exists()
            ? $this->initialized
            : $this->uninitialized;
    }

    public function init(Materialization $materialization, Lock $lock): void
    {
        $this->project()->init($materialization, $lock);
    }

    public function update(Materialization $materialization, Lock $lock): void
    {
        $this->project()->update($materialization, $lock);
    }
}
