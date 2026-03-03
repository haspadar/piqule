<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Override;

final class DefaultConfig implements Config
{
    private const array DEFAULTS = [
        'ci.php.matrix' => ['8.3'],
        'ci.php.test_version' => ['8.3'],
        'ci.piqule_bin' => 'bin/piqule',
        'ci.pr.max_lines_changed' => 250,
        'coverage.patch.target' => 80,
        'coverage.patch.threshold' => 5,
        'coverage.project.target' => 80,
        'coverage.project.threshold' => 2,
        'docker.image' => 'ghcr.io/haspadar/piqule-infra@sha256:a7d41e9fef08156778df6f9172145970a617962bee9e17f1484ebc9b41f6ac29',
        'hadolint.failure_threshold' => 'error',
        'hadolint.ignore' => ['vendor', 'node_modules', '.git'],
        'hadolint.ignored_yaml' => '[]',
        'hadolint.override.error_yaml' => '[]',
        'hadolint.override.warning_yaml' => '[]',
        'hadolint.patterns' => ['Dockerfile*'],
        'infection.php_options' => '-d memory_limit=1G',
        'infection.source.directories' => ['../../src'],
        'infection.timeout' => '30',
        'jsonlint.compact' => 'true',
        'jsonlint.continue' => 'true',
        'jsonlint.duplicate_keys' => 'false',
        'jsonlint.mode' => ['json5'],
        'jsonlint.patterns' => [
            '**/*.json',
            '**/*.json5',
            '**/*.jsonc',
            '!**/vendor/**',
            '!**/node_modules/**',
            '!**/.git/**',
            '!**/coverage/**',
            '!**/build/**',
            '!**/var/**',
        ],
        'markdownlint.ignores' => ['**/vendor/**', '**/node_modules/**', '**/coverage/**', '**/.git/**'],
        'php_cs_fixer.allow_unsupported' => ['true'],
        'php_cs_fixer.exclude' => ['vendor', 'tests'],
        'php_cs_fixer.paths' => ['../..'],
        'phpcs.excludes' => ['vendor/*', 'tests/*'],
        'phpcs.files' => ['../../src'],
        'phpmd.class_complexity' => [50],
        'phpmd.class_length' => [200],
        'phpmd.cyclomatic' => [10],
        'phpmd.max_fields' => [10],
        'phpmd.max_methods' => [10],
        'phpmd.max_parameters' => [5],
        'phpmd.method_length' => [50],
        'phpmd.npath' => [200],
        'phpmd.paths' => ['src'],
        'phpmetrics.complexity.max_cyclomatic_per_method' => [10],
        'phpmetrics.complexity.max_weighted_methods_per_class' => [20],
        'phpmetrics.coupling.max_afferent' => [10],
        'phpmetrics.coupling.max_efferent' => [10],
        'phpmetrics.excludes' => ['vendor', 'tests', 'build', 'bin', 'var', 'node_modules'],
        'phpmetrics.extensions' => ['php'],
        'phpmetrics.halstead.max_bugs_per_method' => [0.5],
        'phpmetrics.halstead.max_difficulty_per_method' => [15],
        'phpmetrics.halstead.max_effort_per_method' => [15000],
        'phpmetrics.halstead.max_volume_per_method' => [1000],
        'phpmetrics.includes' => ['../../src'],
        'phpmetrics.inheritance.max_depth' => [3],
        'phpmetrics.report.html' => ['html'],
        'phpmetrics.report.json' => ['phpmetrics.json'],
        'phpmetrics.size.max_loc_per_class' => [1000],
        'phpmetrics.size.max_logical_loc_per_class' => [600],
        'phpmetrics.size.max_logical_loc_per_method' => [20],
        'phpmetrics.structure.max_methods_per_class' => [10],
        'phpstan.level' => [9],
        'phpstan.memory' => '1G',
        'phpstan.paths' => ['../../src'],
        'phpunit.source.include' => ['../../src'],
        'phpunit.testsuites.integration' => ['../../tests/Integration'],
        'phpunit.testsuites.unit' => ['../../tests/Unit'],
        'psalm.error_level' => [1],
        'psalm.project.directories' => ['../../src'],
        'psalm.project.ignore' => ['../../vendor'],
        'shellcheck.exclude' => [],
        'shellcheck.external_sources' => 'true',
        'shellcheck.ignore_dirs' => ['vendor', 'node_modules', '.git', 'coverage', 'build', 'var'],
        'shellcheck.severity' => 'warning',
        'shellcheck.shell' => 'bash',
        'shellcheck.source_path' => '.',
        'typos.exclude' => ['.git/', 'vendor/', 'node_modules/'],
        'typos.ignore_re' => ['vendor/.*'],
        'yamllint.ignore' => ['vendor/**', 'node_modules/**', 'build/**', 'var/**', '.piqule/**/html/**', '.piqule/**/coverage-report/**'],
        'yamllint.line_length.max' => [120],
    ];

    #[Override]
    public function has(string $name): bool
    {
        return array_key_exists($name, self::DEFAULTS);
    }

    #[Override]
    public function list(string $name): array
    {
        if (!$this->has($name)) {
            return [];
        }

        /** @var list<int|float|string|bool>|scalar $value */
        $value = self::DEFAULTS[$name];

        if (is_scalar($value)) {
            return [$value];
        }

        /** @var list<int|float|string|bool> $value */
        return $value;
    }
}
