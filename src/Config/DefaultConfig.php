<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\Config\Dirs\GlobDirs;
use Haspadar\Piqule\Config\Dirs\NegatedGlobDirs;
use Haspadar\Piqule\Config\Dirs\ProjectDirs;
use Haspadar\Piqule\Config\Dirs\TrailingGlobDirs;
use Haspadar\Piqule\Config\Dirs\TrailingSlashDirs;
use Haspadar\Piqule\PiquleException;
use Override;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Built-in configuration with all declared keys and their default values.
 *
 * Example:
 *
 *     new DefaultConfig();
 *
 *     new DefaultConfig(paths: new ConfigPaths(composerJson: '/path/to/composer.json'));
 */
final class DefaultConfig implements Config
{
    /** @var array<string, scalar|list<scalar>>|null */
    private ?array $cache;

    /**
     * Initializes with source directories, exclusions, and config paths
     *
     * @param list<string> $phpSrc
     * @param list<string> $exclude
     */
    public function __construct(
        private readonly array $phpSrc = [],
        private readonly array $exclude = [],
        private readonly ConfigPaths $paths = new ConfigPaths(),
    ) {
        $this->cache = null;
    }

    #[Override]
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->defaults());
    }

    /** @return list<int|float|string|bool> */
    #[Override]
    public function list(string $name): array
    {
        if (!$this->has($name)) {
            return [];
        }

        $value = $this->defaults()[$name];

        return is_scalar($value)
            ? [$value]
            : $value;
    }

    #[Override]
    public function toArray(): array
    {
        return $this->defaults();
    }

    /** Returns the configuration paths */
    public function configPaths(): ConfigPaths
    {
        return $this->paths;
    }

    /**
     * Parses YAML and computes all defaults with dynamic path derivations
     *
     * @throws PiquleException
     * @return array<string, scalar|list<scalar>>
     */
    private function defaults(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        try {
            /** @var array<string, mixed> $yaml */
            $yaml = Yaml::parseFile($this->paths->configYaml());
        } catch (ParseException $e) {
            throw new PiquleException(
                sprintf('Failed to parse config "%s": %s', $this->paths->configYaml(), $e->getMessage()),
                0,
                $e,
            );
        }

        if (!is_array($yaml) || !array_key_exists('defaults', $yaml) || !is_array($yaml['defaults'])) {
            throw new PiquleException('Missing "defaults" section in config.yaml');
        }

        /** @var array<string, mixed> $base */
        $base = $yaml['defaults'];

        /** @var list<string> $resolvedPhpSrc */
        $resolvedPhpSrc = $this->phpSrc !== []
            ? $this->phpSrc
            : ($base['php.src'] ?? []);

        /** @var list<string> $resolvedExclude */
        $resolvedExclude = $this->exclude !== []
            ? $this->exclude
            : ($base['exclude'] ?? []);

        /** @var array<string, scalar|list<scalar>> $defaults */
        $defaults = array_merge($base, $this->dynamic($resolvedPhpSrc, $resolvedExclude));
        $this->cache = $defaults;

        return $defaults;
    }

    /**
     * @param list<string> $phpSrc
     * @param list<string> $exclude
     * @return array<string, scalar|list<scalar>>
     */
    private function dynamic(array $phpSrc, array $exclude): array
    {
        $projectIncludes = (new ProjectDirs($phpSrc))->toList();

        return [
            'php.src' => $phpSrc,
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
            'phpcs.root_namespace' => (new ComposerRootNamespace($this->paths->composerJson()))->toString(),
            'phpmd.paths' => $phpSrc,
            'phpmetrics.includes' => $projectIncludes,
            'phpmetrics.excludes' => $exclude,
            'phpstan.paths' => $projectIncludes,
            'phpunit.source.include' => $projectIncludes,
            'psalm.project.directories' => $projectIncludes,
            'psalm.project.ignore' => (new ProjectDirs($exclude))->toList(),
            'infection.source.directories' => $projectIncludes,
            'shellcheck.ignore_dirs' => $exclude,
            'sonar.sources' => $phpSrc,
            'typos.exclude' => (new TrailingSlashDirs($exclude))->toList(),
            'yamllint.ignore' => array_merge(
                (new TrailingGlobDirs($exclude))->toList(),
                ['.piqule/**/html/**', '.piqule/**/coverage-report/**'],
            ),
        ];
    }
}
