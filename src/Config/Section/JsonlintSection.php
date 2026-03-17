<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Haspadar\Piqule\Config\Dirs\NegatedGlobDirs;
use Override;

/**
 * Default configuration for jsonlint
 */
final readonly class JsonlintSection implements ConfigSection
{
    /** @param list<string> $excludes */
    public function __construct(private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'jsonlint.compact' => true,
            'jsonlint.continue' => true,
            'jsonlint.duplicate_keys' => false,
            'jsonlint.mode' => ['json5'],
            'jsonlint.patterns' => array_merge(
                ['**/*.json', '**/*.json5', '**/*.jsonc'],
                (new NegatedGlobDirs($this->excludes))->toList(),
            ),
            'jsonlint.enabled' => true,
        ];
    }
}
