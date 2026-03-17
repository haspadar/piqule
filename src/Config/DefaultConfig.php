<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\Config\Dirs\GlobDirs;
use Haspadar\Piqule\Config\Dirs\ProjectDirs;
use Override;
use Haspadar\Piqule\Config\Section\ActionlintSection;
use Haspadar\Piqule\Config\Section\CiSection;
use Haspadar\Piqule\Config\Section\CoverageSection;
use Haspadar\Piqule\Config\Section\DockerSection;
use Haspadar\Piqule\Config\Section\HadolintSection;
use Haspadar\Piqule\Config\Section\InfectionSection;
use Haspadar\Piqule\Config\Section\JsonlintSection;
use Haspadar\Piqule\Config\Section\MarkdownlintSection;
use Haspadar\Piqule\Config\Section\PhpCsFixerSection;
use Haspadar\Piqule\Config\Section\PhpCsSection;
use Haspadar\Piqule\Config\Section\PhpMdSection;
use Haspadar\Piqule\Config\Section\PhpMetricsSection;
use Haspadar\Piqule\Config\Section\PhpStanSection;
use Haspadar\Piqule\Config\Section\PhpUnitSection;
use Haspadar\Piqule\Config\Section\PsalmSection;
use Haspadar\Piqule\Config\Section\ShellcheckSection;
use Haspadar\Piqule\Config\Section\TyposSection;
use Haspadar\Piqule\Config\Section\YamllintSection;

/**
 * Built-in configuration with all declared keys and their default values
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

        $sections = [
            new CiSection($phpVersion),
            new CoverageSection(),
            new DockerSection(),
            new ActionlintSection(),
            new HadolintSection($dirsExclude),
            new JsonlintSection($dirsExclude),
            new MarkdownlintSection($dirsExclude),
            new ShellcheckSection($dirsExclude),
            new TyposSection($dirsExclude),
            new YamllintSection($dirsExclude),
            new PhpCsFixerSection($dirsExclude),
            new PhpCsSection($includes, $excludes),
            new PhpMdSection($includes),
            new PhpMetricsSection($includes, $dirsExclude),
            new PhpStanSection($includes),
            new PhpUnitSection($includes),
            new PsalmSection($includes, $dirsExclude),
            new InfectionSection($includes),
        ];

        $this->defaults = array_merge(
            ['dirs.include' => $dirsInclude, 'dirs.exclude' => $dirsExclude, 'php.version' => $phpVersion],
            ...array_map(fn($s) => $s->toArray(), $sections),
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
}
