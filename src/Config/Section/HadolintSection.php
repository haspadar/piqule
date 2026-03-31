<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for hadolint
 */
final readonly class HadolintSection implements ConfigSection
{
    /** @param list<string> $excludes */
    public function __construct(private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'hadolint.failure_threshold' => 'error',
            'hadolint.ignore' => $this->excludes,
            'hadolint.ignored_yaml' => '[]',
            'hadolint.override.error_yaml' => '[]',
            'hadolint.override.warning_yaml' => '[]',
            'hadolint.patterns' => ['Dockerfile*'],
            'hadolint.enabled' => true,
        ];
    }
}
