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
    private readonly array $defaults;

    /**
     * @param list<string> $include
     * @param list<string> $exclude
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function __construct(
        array $include = [],
        array $exclude = [],
        private readonly string $composerJson = '',
    ) {
        /** @var array<string, mixed> $yaml */
        $yaml = Yaml::parseFile(dirname(__DIR__, 2) . '/templates/always/.piqule/config.yaml');

        /** @var array<string, mixed> $base */
        $base = isset($yaml['defaults']) && is_array($yaml['defaults']) ? $yaml['defaults'] : [];

        /** @var list<string> $resolvedInclude */
        $resolvedInclude = $include !== [] ? $include : (isset($base['php.src']) && is_array($base['php.src']) ? $base['php.src'] : []);
        /** @var list<string> $resolvedExclude */
        $resolvedExclude = $exclude !== [] ? $exclude : (isset($base['exclude']) && is_array($base['exclude']) ? $base['exclude'] : []);

        $projectIncludes = (new ProjectDirs($resolvedInclude))->toList();

        $dynamic = [
            'php.src' => $resolvedInclude,
            'exclude' => $resolvedExclude,
            'hadolint.ignore' => $resolvedExclude,
            'jsonlint.patterns' => array_merge(
                ['**/*.json', '**/*.json5', '**/*.jsonc'],
                (new NegatedGlobDirs($resolvedExclude))->toList(),
            ),
            'markdownlint.ignores' => (new TrailingGlobDirs($resolvedExclude))->toList(),
            'php_cs_fixer.exclude' => $resolvedExclude,
            'phpcs.excludes' => (new GlobDirs($resolvedExclude))->toList(),
            'phpcs.files' => $projectIncludes,
            'phpcs.root_namespace' => (new ComposerRootNamespace($composerJson))->toString(),
            'phpmd.paths' => $resolvedInclude,
            'phpmetrics.includes' => $projectIncludes,
            'phpmetrics.excludes' => $resolvedExclude,
            'phpstan.paths' => $projectIncludes,
            'phpunit.source.include' => $projectIncludes,
            'psalm.project.directories' => $projectIncludes,
            'psalm.project.ignore' => (new ProjectDirs($resolvedExclude))->toList(),
            'infection.source.directories' => $projectIncludes,
            'shellcheck.ignore_dirs' => $resolvedExclude,
            'sonar.sources' => $resolvedInclude,
            'typos.exclude' => (new TrailingSlashDirs($resolvedExclude))->toList(),
            'yamllint.ignore' => array_merge(
                (new TrailingGlobDirs($resolvedExclude))->toList(),
                ['.piqule/**/html/**', '.piqule/**/coverage-report/**'],
            ),
        ];

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

    public function composerJson(): string
    {
        return $this->composerJson;
    }
}
