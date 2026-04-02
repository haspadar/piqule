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
        $this->template = (string) file_get_contents(
            dirname(__DIR__, 3) . '/templates/always/.piqule/config.yaml',
        );
    }

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof Config) {
            return false;
        }

        $parsed = Yaml::parse(
            (new ConfiguredFile(new TextFile('.piqule/config.yaml', $this->template), $other))->contents(),
        );

        return ($parsed['defaults'][$this->key] ?? null) === $this->expected;
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

        $parsed = Yaml::parse(
            (new ConfiguredFile(new TextFile('.piqule/config.yaml', $this->template), $other))->contents(),
        );

        $actual = $parsed['defaults'][$this->key] ?? null;

        return "\nExpected: " . var_export($this->expected, true)
            . "\nBut was:  " . var_export($actual, true);
    }
}
