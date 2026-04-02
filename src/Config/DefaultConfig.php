<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\Config\Dirs\GlobDirs;
use Haspadar\Piqule\Config\Dirs\NegatedGlobDirs;
use Haspadar\Piqule\Config\Dirs\ProjectDirs;
use Haspadar\Piqule\Config\Dirs\TrailingGlobDirs;
use Haspadar\Piqule\Config\Dirs\TrailingSlashDirs;
use Override;
use Symfony\Component\Yaml\Yaml;

/**
 * Built-in configuration with all declared keys and their default values.
 *
 * Example:
 *
 *     new DefaultConfig(
 *         include: ['src', 'lib'],
 *         exclude: ['vendor', 'tests', '.git', 'legacy'],
 *     );
 */
final class DefaultConfig implements Config
{
    /** @var array<string, scalar|list<scalar>> */
    private readonly array $defaults;

    /**
     * @param list<string> $include
     * @param list<string> $exclude
     */
    public function __construct(
        array $include = ['src'],
        array $exclude = ['vendor', 'tests', '.git'],
        string $composerJson = '',
    ) {
        $projectIncludes = (new ProjectDirs($include))->toList();

        $dynamic = [
            'php.src' => $include,
            'exclude' => $exclude,
            'hadolint.ignore' => $exclude,
            'jsonlint.patterns' => array_merge(
                ['**/*.json', '**/*.json5', '**/*.jsonc'],
                (new NegatedGlobDirs($exclude))->toList(),
            ),
            'markdownlint.ignores' => (new TrailingGlobDirs($exclude))->toList(),
            'php_cs_fixer.exclude' => $exclude,
            'phpcs.excludes' => (new GlobDirs($exclude))->toList(),
            'phpcs.files' => $projectIncludes,
            'phpcs.root_namespace' => (new ComposerRootNamespace($composerJson))->toString(),
            'phpmd.paths' => $include,
            'phpmetrics.includes' => $projectIncludes,
            'phpmetrics.excludes' => $exclude,
            'phpstan.paths' => $projectIncludes,
            'phpunit.source.include' => $projectIncludes,
            'psalm.project.directories' => $projectIncludes,
            'psalm.project.ignore' => (new ProjectDirs($exclude))->toList(),
            'infection.source.directories' => $projectIncludes,
            'shellcheck.ignore_dirs' => $exclude,
            'sonar.sources' => $include,
            'typos.exclude' => (new TrailingSlashDirs($exclude))->toList(),
            'yamllint.ignore' => array_merge(
                (new TrailingGlobDirs($exclude))->toList(),
                ['.piqule/**/html/**', '.piqule/**/coverage-report/**'],
            ),
        ];

        /** @var array<string, scalar|list<scalar>> $base */
        $base = Yaml::parseFile(dirname(__DIR__, 2) . '/templates/always/.piqule/config.yaml')['defaults'] ?? [];

        /** @var array<string, scalar|list<scalar>> $defaults */
        $defaults = array_merge($base, $dynamic);
        $this->defaults = $defaults;
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

        return is_scalar($value)
            ? [$value]
            : $value;
    }

    #[Override]
    public function toArray(): array
    {
        return $this->defaults;
    }
}
