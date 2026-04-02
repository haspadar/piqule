<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Constraint\Config;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\File\ConfiguredFile;
use Haspadar\Piqule\File\TextFile;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Yaml\Yaml;

/**
 * Asserts that a rendered config.yaml contains an expected value under defaults.<key>.
 */
final class HasConfigYamlKey extends Constraint
{
    private readonly string $template;

    public function __construct(
        private readonly string $key,
        private readonly mixed $expected,
    ) {
        $templatePath = dirname(__DIR__, 3) . '/templates/always/.piqule/config.yaml';
        $contents = file_get_contents($templatePath);

        if ($contents === false) {
            throw new \RuntimeException("Cannot read template: {$templatePath}");
        }

        $this->template = $contents;
    }

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof Config) {
            return false;
        }

        return ($this->render($other)['defaults'][$this->key] ?? null) === $this->expected;
    }

    public function toString(): string
    {
        return 'has defaults.' . $this->key . ' === ' . var_export($this->expected, true);
    }

    protected function additionalFailureDescription(mixed $other): string
    {
        if (!$other instanceof Config) {
            return "\nExpected a Config instance";
        }

        $actual = $this->render($other)['defaults'][$this->key] ?? null;

        return "\nExpected: " . var_export($this->expected, true)
            . "\nBut was:  " . var_export($actual, true);
    }

    /** @return array<string, mixed> */
    private function render(Config $config): mixed
    {
        return Yaml::parse(
            (new ConfiguredFile(new TextFile('.piqule/config.yaml', $this->template), $config))->contents(),
        );
    }
}
