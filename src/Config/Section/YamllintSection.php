<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Haspadar\Piqule\Config\Dirs\TrailingGlobDirs;
use Override;

/**
 * Default configuration for yamllint
 */
final readonly class YamllintSection implements ConfigSection
{
    /** @param list<string> $excludes */
    public function __construct(private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'yamllint.ignore' => array_merge(
                (new TrailingGlobDirs($this->excludes))->toList(),
                ['.piqule/**/html/**', '.piqule/**/coverage-report/**'],
            ),
            'yamllint.line_length.max' => [120],
            'yamllint.enabled' => true,
        ];
    }
}
