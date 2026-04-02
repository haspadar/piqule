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
    /** @var list<string> */
    private array $phpSrc;

    /** @var list<string> */
    private array $exclude;

    /**
     * @param array<string, mixed> $overrides
     * @param array<string, mixed> $appends
     * @throws PiquleException
     */
    public function __construct(array $overrides, array $appends, DefaultConfig $defaults)
    {
        $phpSrc = isset($overrides['php.src']) && is_array($overrides['php.src'])
            ? $this->toStringList($overrides['php.src'], 'override.php.src')
            : $this->toStringList($defaults->list('php.src'), 'php.src');

        $exclude = isset($overrides['exclude']) && is_array($overrides['exclude'])
            ? $this->toStringList($overrides['exclude'], 'override.exclude')
            : $this->toStringList($defaults->list('exclude'), 'exclude');

        if (isset($appends['exclude']) && is_array($appends['exclude'])) {
            $extra = $this->toStringList($appends['exclude'], 'append.exclude');
            /** @var list<string> $exclude */
            $exclude = array_values(array_unique(array_merge($exclude, $extra)));
        }

        if (isset($appends['php.src']) && is_array($appends['php.src'])) {
            $extra = $this->toStringList($appends['php.src'], 'append.php.src');
            /** @var list<string> $phpSrc */
            $phpSrc = array_values(array_unique(array_merge($phpSrc, $extra)));
        }

        $this->phpSrc = $phpSrc;
        $this->exclude = $exclude;
    }

    /**
     * @param array<mixed> $value
     * @return list<string>
     * @throws PiquleException
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

    /** @return list<string> */
    public function phpSrc(): array
    {
        return $this->phpSrc;
    }

    /** @return list<string> */
    public function exclude(): array
    {
        return $this->exclude;
    }
}
