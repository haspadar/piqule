<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Haspadar\Piqule\Config\Dirs\TrailingGlobDirs;
use Override;

/**
 * Default configuration for markdownlint
 */
final readonly class MarkdownlintSection implements ConfigSection
{
    /** @param list<string> $excludes */
    public function __construct(private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'markdownlint.ignores' => (new TrailingGlobDirs($this->excludes))->toList(),
            'markdownlint.enabled' => true,
        ];
    }
}
