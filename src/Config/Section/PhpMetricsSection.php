<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for PHP Metrics
 */
final readonly class PhpMetricsSection implements ConfigSection
{
    /**
     * @param list<string> $includes
     * @param list<string> $excludes
     */
    public function __construct(private array $includes, private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'phpmetrics.complexity.max_cyclomatic_per_method' => [10],
            'phpmetrics.complexity.max_weighted_methods_per_class' => [20],
            'phpmetrics.coupling.max_afferent' => [10],
            'phpmetrics.coupling.max_efferent' => [10],
            'phpmetrics.excludes' => $this->excludes,
            'phpmetrics.extensions' => ['php'],
            'phpmetrics.halstead.max_bugs_per_method' => [0.5],
            'phpmetrics.halstead.max_difficulty_per_method' => [15],
            'phpmetrics.halstead.max_effort_per_method' => [15_000],
            'phpmetrics.halstead.max_volume_per_method' => [1000],
            'phpmetrics.includes' => $this->includes,
            'phpmetrics.inheritance.max_depth' => [3],
            'phpmetrics.report.html' => ['html'],
            'phpmetrics.report.json' => ['phpmetrics.json'],
            'phpmetrics.size.max_loc_per_class' => [1000],
            'phpmetrics.size.max_logical_loc_per_class' => [600],
            'phpmetrics.size.max_logical_loc_per_method' => [20],
            'phpmetrics.structure.max_methods_per_class' => [10],
            'phpmetrics.enabled' => true,
        ];
    }
}
