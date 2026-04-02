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
 *     new DefaultConfig();
 *
 *     new DefaultConfig(composerJson: '/path/to/composer.json');
 */
final class DefaultConfig implements Config
{
    /** @var array<string, scalar|list<scalar>> */
    private array $defaults;

    /** @var array<string, scalar|list<scalar>> */
    private array $base;

    /** @var list<string> */
    private array $include;

    /** @var list<string> */
    private array $exclude;

    public function __construct(private readonly string $composerJson = '')
    {
        /** @var array<string, mixed> $yaml */
        $yaml = Yaml::parseFile(dirname(__DIR__, 2) . '/templates/always/.piqule/config.yaml')['defaults'] ?? [];

        /** @var list<string> $include */
        $include = isset($yaml['php.src']) && is_array($yaml['php.src']) ? $yaml['php.src'] : ['src'];
        /** @var list<string> $exclude */
        $exclude = isset($yaml['exclude']) && is_array($yaml['exclude']) ? $yaml['exclude'] : ['vendor', 'tests', '.git'];

        /** @var array<string, scalar|list<scalar>> $base */
        $base = $yaml;

        $this->base = $base;
        $this->include = $include;
        $this->exclude = $exclude;
        $this->defaults = $this->build($include, $exclude, $base);
    }

    /**
     * Returns a new DefaultConfig with include/exclude resolved from a .piqule.yaml file.
     * All derived keys (shellcheck.ignore_dirs, phpstan.paths, etc.) will reflect
     * the project's actual include/exclude lists.
     */
    public function withYaml(string $path): self
    {
        /** @var array<string, mixed> $data */
        $data = Yaml::parseFile($path);

        /** @var array<string, mixed> $overrides */
        $overrides = isset($data['override']) && is_array($data['override']) ? $data['override'] : [];
        /** @var array<string, mixed> $appends */
        $appends = isset($data['append']) && is_array($data['append']) ? $data['append'] : [];

        /** @var list<string> $include */
        $include = isset($overrides['php.src']) && is_array($overrides['php.src'])
            ? $overrides['php.src']
            : $this->include;

        /** @var list<string> $exclude */
        $exclude = isset($overrides['exclude']) && is_array($overrides['exclude'])
            ? $overrides['exclude']
            : $this->exclude;

        if (isset($appends['exclude']) && is_array($appends['exclude'])) {
            /** @var list<string> $extra */
            $extra = $appends['exclude'];
            $exclude = array_values(array_unique(array_merge($exclude, $extra)));
        }

        if (isset($appends['php.src']) && is_array($appends['php.src'])) {
            /** @var list<string> $extra */
            $extra = $appends['php.src'];
            $include = array_values(array_unique(array_merge($include, $extra)));
        }

        $copy = new self($this->composerJson);
        $copy->include = $include;
        $copy->exclude = $exclude;
        $copy->defaults = $copy->build($include, $exclude, $copy->base);

        return $copy;
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

    /**
     * @param list<string> $include
     * @param list<string> $exclude
     * @param array<string, mixed> $base
     * @return array<string, scalar|list<scalar>>
     */
    private function build(array $include, array $exclude, array $base): array
    {
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
            'phpcs.root_namespace' => (new ComposerRootNamespace($this->composerJson))->toString(),
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

        /** @var array<string, scalar|list<scalar>> $defaults */
        $defaults = array_merge($base, $dynamic);

        return $defaults;
    }
}
