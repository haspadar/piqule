<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Envs;

use Haspadar\Piqule\PiquleException;
use Override;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Parses the envs section from a .piqule.yaml file.
 */
final readonly class YamlEnvs implements Envs
{
    /** Initializes with the path to a .piqule.yaml file. */
    public function __construct(private string $path) {}

    #[Override]
    public function vars(): array
    {
        try {
            /** @var mixed $yaml */
            $yaml = Yaml::parseFile($this->path);
        } catch (ParseException $e) {
            throw new PiquleException(
                sprintf('Failed to parse "%s": %s', $this->path, $e->getMessage()),
                0,
                $e,
            );
        }

        /** @var mixed $envs */
        $envs = is_array($yaml)
            ? ($yaml['envs'] ?? [])
            : [];

        if (!is_array($envs)) {
            throw new PiquleException(
                sprintf('Invalid "envs" section in "%s": expected a mapping', $this->path),
            );
        }

        /** @var array<string, string> $vars */
        $vars = [];

        foreach ($envs as $name => $command) {
            if (!is_string($name) || !is_string($command)) {
                throw new PiquleException(
                    sprintf('Each entry in "envs" must be string => string in "%s"', $this->path),
                );
            }

            if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name) !== 1) {
                throw new PiquleException(
                    sprintf('Invalid environment variable name "%s" in "%s"', $name, $this->path),
                );
            }

            $vars[$name] = $command;
        }

        return $vars;
    }
}
