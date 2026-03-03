<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

/**
 * @phpstan-type OverrideMap array{
 *   'ci.php.matrix'?: list<string>,
 *   'ci.php.test_version'?: string,
 *   'ci.piqule_bin'?: string,
 *   'ci.pr.max_lines_changed'?: int,
 *   'coverage.patch.target'?: int|float,
 *   'coverage.patch.threshold'?: int|float,
 *   'coverage.project.target'?: int|float,
 *   'coverage.project.threshold'?: int|float,
 *   'docker.image'?: string,
 *   'hadolint.failure_threshold'?: string,
 *   'hadolint.ignore'?: list<string>,
 *   'hadolint.ignored_yaml'?: string,
 *   'hadolint.override.error_yaml'?: string,
 *   'hadolint.override.warning_yaml'?: string,
 *   'hadolint.patterns'?: list<string>,
 *   'infection.php_options'?: string,
 *   'infection.source.directories'?: list<string>,
 *   'infection.timeout'?: int|float,
 *   'jsonlint.compact'?: bool,
 *   'jsonlint.continue'?: bool,
 *   'jsonlint.duplicate_keys'?: bool,
 *   'jsonlint.mode'?: list<string>,
 *   'jsonlint.patterns'?: list<string>,
 *   'markdownlint.ignores'?: list<string>,
 *   'php_cs_fixer.allow_unsupported'?: list<bool>,
 *   'php_cs_fixer.exclude'?: list<string>,
 *   'php_cs_fixer.paths'?: list<string>,
 *   'phpcs.excludes'?: list<string>,
 *   'phpcs.files'?: list<string>,
 *   'phpmd.class_complexity'?: list<int|float>,
 *   'phpmd.class_length'?: list<int|float>,
 *   'phpmd.cyclomatic'?: list<int|float>,
 *   'phpmd.max_fields'?: list<int|float>,
 *   'phpmd.max_methods'?: list<int|float>,
 *   'phpmd.max_parameters'?: list<int|float>,
 *   'phpmd.method_length'?: list<int|float>,
 *   'phpmd.npath'?: list<int|float>,
 *   'phpmd.paths'?: list<string>,
 *   'phpmetrics.complexity.max_cyclomatic_per_method'?: list<int|float>,
 *   'phpmetrics.complexity.max_weighted_methods_per_class'?: list<int|float>,
 *   'phpmetrics.coupling.max_afferent'?: list<int|float>,
 *   'phpmetrics.coupling.max_efferent'?: list<int|float>,
 *   'phpmetrics.excludes'?: list<string>,
 *   'phpmetrics.extensions'?: list<string>,
 *   'phpmetrics.halstead.max_bugs_per_method'?: list<int|float>,
 *   'phpmetrics.halstead.max_difficulty_per_method'?: list<int|float>,
 *   'phpmetrics.halstead.max_effort_per_method'?: list<int|float>,
 *   'phpmetrics.halstead.max_volume_per_method'?: list<int|float>,
 *   'phpmetrics.includes'?: list<string>,
 *   'phpmetrics.inheritance.max_depth'?: list<int|float>,
 *   'phpmetrics.report.html'?: list<string>,
 *   'phpmetrics.report.json'?: list<string>,
 *   'phpmetrics.size.max_loc_per_class'?: list<int|float>,
 *   'phpmetrics.size.max_logical_loc_per_class'?: list<int|float>,
 *   'phpmetrics.size.max_logical_loc_per_method'?: list<int|float>,
 *   'phpmetrics.structure.max_methods_per_class'?: list<int|float>,
 *   'phpstan.level'?: list<int|float>,
 *   'phpstan.memory'?: string,
 *   'phpstan.paths'?: list<string>,
 *   'phpunit.source.include'?: list<string>,
 *   'phpunit.testsuites.integration'?: list<string>,
 *   'phpunit.testsuites.unit'?: list<string>,
 *   'psalm.error_level'?: list<int|float>,
 *   'psalm.project.directories'?: list<string>,
 *   'psalm.project.ignore'?: list<string>,
 *   'shellcheck.exclude'?: list<string>,
 *   'shellcheck.external_sources'?: bool,
 *   'shellcheck.ignore_dirs'?: list<string>,
 *   'shellcheck.severity'?: string,
 *   'shellcheck.shell'?: string,
 *   'shellcheck.source_path'?: string,
 *   'typos.exclude'?: list<string>,
 *   'typos.ignore_re'?: list<string>,
 *   'yamllint.ignore'?: list<string>,
 *   'yamllint.line_length.max'?: list<int|float>
 * }
 */
final readonly class OverrideConfig implements Config
{
    /**
     * @param OverrideMap $overrides
     */
    public function __construct(
        private Config $defaults,
        private array $overrides,
    ) {}

    #[Override]
    public function has(string $name): bool
    {
        return $this->defaults->has($name);
    }

    #[Override]
    public function list(string $name): array
    {
        if (!$this->defaults->has($name)) {
            throw new PiquleException(
                sprintf('Unknown config key "%s"', $name),
            );
        }

        if (!array_key_exists($name, $this->overrides)) {
            return $this->defaults->list($name);
        }

        return $this->normalizedValue($this->overrides[$name], $name);
    }

    /**
     * @return list<scalar>
     */
    private function normalizedValue(mixed $value, string $name): array
    {
        if (is_scalar($value)) {
            return [$value];
        }

        if (!is_array($value) || !array_is_list($value)) {
            throw new PiquleException(
                sprintf('Override "%s" must be scalar or list<scalar>', $name),
            );
        }

        foreach ($value as $item) {
            if (!is_scalar($item)) {
                throw new PiquleException(
                    sprintf('Override "%s" must contain only scalars', $name),
                );
            }
        }

        /** @var list<scalar> $value */
        return $value;
    }
}
