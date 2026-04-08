<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;

/**
 * Resolves include and exclude lists from .piqule.yaml override/append sections,
 * cascading into DefaultConfig so all derived path keys reflect the project layout.
 */
final readonly class YamlPathKeys
{
    /**
     * @param array<string, mixed> $overrides
     * @param array<string, mixed> $appends
     */
    public function __construct(
        private array $overrides,
        private array $appends,
        private DefaultConfig $defaults,
    ) {}

    /**
     * @throws PiquleException
     * @return list<string>
     */
    public function phpSrc(): array
    {
        $result = array_key_exists('php.src', $this->overrides) && is_array($this->overrides['php.src'])
            ? $this->toStringList(array_values($this->overrides['php.src']), 'override.php.src')
            : $this->toStringList($this->defaults->list('php.src'), 'php.src');

        if (array_key_exists('php.src', $this->appends) && is_array($this->appends['php.src'])) {
            $extra = $this->toStringList(array_values($this->appends['php.src']), 'append.php.src');
            $result = array_values(array_unique(array_merge($result, $extra)));
        }

        return $result;
    }

    /**
     * @throws PiquleException
     * @return list<string>
     */
    public function exclude(): array
    {
        $result = array_key_exists('exclude', $this->overrides) && is_array($this->overrides['exclude'])
            ? $this->toStringList(array_values($this->overrides['exclude']), 'override.exclude')
            : $this->toStringList($this->defaults->list('exclude'), 'exclude');

        if (array_key_exists('exclude', $this->appends) && is_array($this->appends['exclude'])) {
            $extra = $this->toStringList(array_values($this->appends['exclude']), 'append.exclude');
            $result = array_values(array_unique(array_merge($result, $extra)));
        }

        return $result;
    }

    /**
     * @param list<mixed> $value
     * @throws PiquleException
     * @return list<string>
     */
    private function toStringList(array $value, string $key): array
    {
        $result = [];

        foreach ($value as $i => $item) {
            if (!is_string($item)) {
                throw new PiquleException(
                    sprintf('"%s" must be a list of strings, got %s at index %d', $key, get_debug_type($item), $i),
                );
            }

            $result[] = $item;
        }

        return $result;
    }
}
