<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Skill;

use Haspadar\Piqule\Runnable;
use Haspadar\Piqule\Storage\Reaction\StorageReaction;
use Haspadar\Piqule\Storage\SectionStorage;
use Haspadar\Piqule\Storage\Storage;
use Override;

/**
 * Installs the piqule skill body into the configured agent targets.
 */
final readonly class SkillInstall implements Runnable
{
    /**
     * Initializes with the skill files to install and the storage that receives them.
     *
     * @param list<SkillFile> $files
     */
    public function __construct(
        private array $files,
        private Storage $storage,
        private StorageReaction $reaction,
    ) {}

    #[Override]
    public function run(): void
    {
        $current = $this->storage;

        foreach ($this->files as $file) {
            $current = (new SectionStorage($current, $this->reaction, $file->pattern()))->write($file);
        }
    }
}
