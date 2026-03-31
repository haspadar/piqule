<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for shellcheck
 */
final readonly class ShellcheckSection implements ConfigSection
{
    /** @param list<string> $excludes */
    public function __construct(private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'shellcheck.exclude' => [],
            'shellcheck.external_sources' => 'true',
            'shellcheck.ignore_dirs' => $this->excludes,
            'shellcheck.severity' => 'warning',
            'shellcheck.shell' => 'bash',
            'shellcheck.source_path' => '.',
            'shellcheck.enabled' => true,
        ];
    }
}
