<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Override;

/**
 * Built-in configuration with all declared keys and their default values
 *
 * @SuppressWarnings("ExcessiveClassLength")
 * @SuppressWarnings("TooManyMethods")
 */
final class DefaultConfig implements Config
{
    /** @var array<string, scalar|list<scalar>> */
    private readonly array $defaults;

    public function __construct()
    {
        $dirsInclude = ['src'];
        $dirsExclude = ['vendor', 'tests', '.git'];
        $phpVersion = ['8.3'];
        $includes = (new ProjectDirs($dirsInclude))->toList();
        $excludes = (new GlobDirs($dirsExclude))->toList();

        $this->defaults = array_merge(
            ['dirs.include' => $dirsInclude, 'dirs.exclude' => $dirsExclude, 'php.version' => $phpVersion],
            $this->ci($phpVersion),
            $this->coverage(),
            $this->docker(),
            $this->actionlint(),
            $this->hadolint($dirsExclude),
            $this->jsonlint($dirsExclude),
            $this->markdownlint($dirsExclude),
            $this->shellcheck($dirsExclude),
            $this->typos($dirsExclude),
            $this->yamllint($dirsExclude),
            $this->phpCsFixer($dirsExclude),
            $this->phpCs($includes, $excludes),
            $this->phpMd($includes),
            $this->phpMetrics($includes, $dirsExclude),
            $this->phpStan($includes),
            $this->phpUnit($includes),
            $this->psalm($includes, $dirsExclude),
            $this->infection($includes),
        );
    }

    /**
     * Checks whether a configuration key exists in built-in defaults
     */
    #[Override]
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->defaults);
    }

    /**
     * Returns the configuration value as a list
     *
     * @return list<int|float|string|bool>
     */
    #[Override]
    public function list(string $name): array
    {
        if (!$this->has($name)) {
            return [];
        }

        $value = $this->defaults[$name];

        return is_scalar($value) ? [$value] : $value;
    }

    /**
     * @param list<string> $phpVersion
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function ci(array $phpVersion): array
    {
        return [
            'ci.php.matrix' => $phpVersion,
            'ci.php.test_version' => $phpVersion,
            'ci.piqule_bin' => 'vendor/bin/piqule',
            'ci.pr.max_lines_changed' => 250,
        ];
    }

    /** @return array<string, scalar|list<scalar>> */
    private function coverage(): array
    {
        return [
            'coverage.patch.target' => 80,
            'coverage.patch.threshold' => 5,
            'coverage.project.target' => 80,
            'coverage.project.threshold' => 2,
        ];
    }

    /** @return array<string, scalar|list<scalar>> */
    private function docker(): array
    {
        return [
            'docker.image' => 'ghcr.io/haspadar/piqule-infra@sha256:a7d41e9fef08156778df6f9172145970a617962bee9e17f1484ebc9b41f6ac29',
        ];
    }

    /** @return array<string, scalar|list<scalar>> */
    private function actionlint(): array
    {
        return [
            'actionlint.enabled' => true,
        ];
    }

    /**
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function hadolint(array $excludes): array
    {
        return [
            'hadolint.failure_threshold' => 'error',
            'hadolint.ignore' => $excludes,
            'hadolint.ignored_yaml' => '[]',
            'hadolint.override.error_yaml' => '[]',
            'hadolint.override.warning_yaml' => '[]',
            'hadolint.patterns' => ['Dockerfile*'],
            'hadolint.enabled' => true,
        ];
    }

    /**
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function jsonlint(array $excludes): array
    {
        return [
            'jsonlint.compact' => 'true',
            'jsonlint.continue' => 'true',
            'jsonlint.duplicate_keys' => 'false',
            'jsonlint.mode' => ['json5'],
            'jsonlint.patterns' => array_merge(
                ['**/*.json', '**/*.json5', '**/*.jsonc'],
                (new NegatedGlobDirs($excludes))->toList(),
            ),
            'jsonlint.enabled' => true,
        ];
    }

    /**
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function markdownlint(array $excludes): array
    {
        return [
            'markdownlint.ignores' => (new TrailingGlobDirs($excludes))->toList(),
            'markdownlint.enabled' => true,
        ];
    }

    /**
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function shellcheck(array $excludes): array
    {
        return [
            'shellcheck.exclude' => [],
            'shellcheck.external_sources' => 'true',
            'shellcheck.ignore_dirs' => $excludes,
            'shellcheck.severity' => 'warning',
            'shellcheck.shell' => 'bash',
            'shellcheck.source_path' => '.',
            'shellcheck.enabled' => true,
        ];
    }

    /**
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function typos(array $excludes): array
    {
        return [
            'typos.exclude' => (new TrailingSlashDirs($excludes))->toList(),
            'typos.enabled' => true,
        ];
    }

    /**
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function yamllint(array $excludes): array
    {
        return [
            'yamllint.ignore' => array_merge(
                (new TrailingGlobDirs($excludes))->toList(),
                ['.piqule/**/html/**', '.piqule/**/coverage-report/**'],
            ),
            'yamllint.line_length.max' => [120],
            'yamllint.enabled' => true,
        ];
    }

    /**
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function phpCsFixer(array $excludes): array
    {
        return [
            'php_cs_fixer.allow_unsupported' => ['true'],
            'php_cs_fixer.exclude' => $excludes,
            'php_cs_fixer.paths' => ['../..'],
            'php-cs-fixer.enabled' => true,
        ];
    }

    /**
     * @param list<string> $includes
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function phpCs(array $includes, array $excludes): array
    {
        return [
            'phpcs.excludes' => $excludes,
            'phpcs.files' => $includes,
            'phpcs.enabled' => true,
        ];
    }

    /**
     * @param list<string> $includes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function phpMd(array $includes): array
    {
        return [
            'phpmd.class_complexity' => [50],
            'phpmd.class_length' => [200],
            'phpmd.cyclomatic' => [10],
            'phpmd.max_fields' => [10],
            'phpmd.max_methods' => [10],
            'phpmd.max_parameters' => [5],
            'phpmd.method_length' => [50],
            'phpmd.npath' => [200],
            'phpmd.paths' => $includes,
            'phpmd.enabled' => true,
        ];
    }

    /**
     * @param list<string> $includes
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function phpMetrics(array $includes, array $excludes): array
    {
        return [
            'phpmetrics.complexity.max_cyclomatic_per_method' => [10],
            'phpmetrics.complexity.max_weighted_methods_per_class' => [20],
            'phpmetrics.coupling.max_afferent' => [10],
            'phpmetrics.coupling.max_efferent' => [10],
            'phpmetrics.excludes' => $excludes,
            'phpmetrics.extensions' => ['php'],
            'phpmetrics.halstead.max_bugs_per_method' => [0.5],
            'phpmetrics.halstead.max_difficulty_per_method' => [15],
            'phpmetrics.halstead.max_effort_per_method' => [15000],
            'phpmetrics.halstead.max_volume_per_method' => [1000],
            'phpmetrics.includes' => $includes,
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

    /**
     * @param list<string> $includes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function phpStan(array $includes): array
    {
        return [
            'phpstan.level' => [9],
            'phpstan.memory' => '1G',
            'phpstan.paths' => $includes,
            'phpstan.enabled' => true,
        ];
    }

    /**
     * @param list<string> $includes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function phpUnit(array $includes): array
    {
        return [
            'phpunit.source.include' => $includes,
            'phpunit.testsuites.integration' => ['../../tests/Integration'],
            'phpunit.testsuites.unit' => ['../../tests/Unit'],
            'phpunit.enabled' => true,
        ];
    }

    /**
     * @param list<string> $includes
     * @param list<string> $excludes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function psalm(array $includes, array $excludes): array
    {
        return [
            'psalm.error_level' => [1],
            'psalm.project.directories' => $includes,
            'psalm.project.ignore' => (new ProjectDirs($excludes))->toList(),
            'psalm.enabled' => true,
        ];
    }

    /**
     * @param list<string> $includes
     *
     * @return array<string, scalar|list<scalar>>
     */
    private function infection(array $includes): array
    {
        return [
            'infection.php_options' => '-d memory_limit=1G',
            'infection.source.directories' => $includes,
            'infection.timeout' => '30',
            'infection.enabled' => true,
        ];
    }
}
