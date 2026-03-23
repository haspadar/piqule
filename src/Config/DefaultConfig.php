<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\Config\Dirs\GlobDirs;
use Haspadar\Piqule\Config\Dirs\ProjectDirs;
use Haspadar\Piqule\Config\Dirs\TrailingGlobDirs;
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
use Haspadar\Piqule\Config\Section\SonarSection;
use Haspadar\Piqule\Config\Section\TyposSection;
use Haspadar\Piqule\Config\Section\YamllintSection;
use Override;

/**
 * Built-in configuration with all declared keys and their default values
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
    ) {
        $phpVersion = ['8.3'];
        $projectIncludes = (new ProjectDirs($include))->toList();
        $globExcludes = (new GlobDirs($exclude))->toList();

        $sections = [
            new CiSection($phpVersion, $phpVersion),
            new CoverageSection(),
            new DockerSection(),
            new ActionlintSection(),
            new HadolintSection($exclude),
            new JsonlintSection($exclude),
            new MarkdownlintSection($exclude),
            new ShellcheckSection($exclude),
            new TyposSection($exclude),
            new YamllintSection($exclude),
            new PhpCsFixerSection($exclude),
            new PhpCsSection($projectIncludes, $globExcludes),
            new PhpMdSection($include),
            new PhpMetricsSection($projectIncludes, $exclude),
            new PhpStanSection($projectIncludes),
            new PhpUnitSection($projectIncludes),
            new PsalmSection($projectIncludes, $exclude),
            new InfectionSection($projectIncludes),
            new SonarSection($projectIncludes, (new TrailingGlobDirs($exclude))->toList()),
        ];

        /** @var array<string, scalar|list<scalar>> $defaults */
        $defaults = array_merge(
            ['dirs.include' => $include, 'dirs.exclude' => $exclude, 'php.version' => $phpVersion],
            ...array_map(fn($s) => $s->toArray(), $sections),
        );
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

        return is_scalar($value) ? [$value] : $value;
    }
}
