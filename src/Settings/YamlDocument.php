<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Settings;

use Haspadar\Piqule\PiquleException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Reads a yaml file and exposes its top-level mapping.
 *
 * Example:
 *
 *     (new YamlDocument('/path/to/.piqule.yaml'))->section('override');
 */
final readonly class YamlDocument
{
    /**
     * Initializes with the path to the yaml file.
     *
     * @param string $path Filesystem path to the yaml file
     */
    public function __construct(private string $path) {}

    /**
     * Returns the named top-level mapping section, or empty if absent.
     *
     * @param string $name Section name to read from the top-level mapping
     * @throws PiquleException
     * @return array<string, mixed>
     */
    public function section(string $name): array
    {
        $document = $this->mapping();

        if (!array_key_exists($name, $document)) {
            return [];
        }

        if (!is_array($document[$name])) {
            throw new PiquleException(
                sprintf('Section "%s" in "%s" must be a mapping', $name, $this->path),
            );
        }

        /** @var array<string, mixed> $section */
        $section = $document[$name];

        return $section;
    }

    /**
     * Parses the yaml file once and returns the top-level mapping.
     *
     * @throws PiquleException
     * @return array<string, mixed>
     */
    private function mapping(): array
    {
        try {
            /** @var mixed $parsed */
            $parsed = Yaml::parseFile($this->path);
        } catch (ParseException $e) {
            throw new PiquleException(
                sprintf('Failed to parse "%s": %s', $this->path, $e->getMessage()),
                0,
                $e,
            );
        }

        if ($parsed === null) {
            return [];
        }

        if (!is_array($parsed) || ($parsed !== [] && array_is_list($parsed))) {
            throw new PiquleException(
                sprintf('Expected a mapping at the top of "%s"', $this->path),
            );
        }

        /** @var array<string, mixed> $document */
        $document = $parsed;

        return $document;
    }
}
