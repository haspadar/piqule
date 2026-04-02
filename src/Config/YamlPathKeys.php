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
    private array $include;

    /** @var list<string> */
    private array $exclude;

    /**
     * @param array<string, mixed> $overrides
     * @param array<string, mixed> $appends
     * @throws PiquleException
     */
    public function __construct(array $overrides, array $appends, DefaultConfig $defaults)
    {
        /** @var list<string> $include */
        $include = isset($overrides['php.src']) && is_array($overrides['php.src'])
            ? $overrides['php.src']
            : $defaults->list('php.src');

        /** @var list<string> $exclude */
        $exclude = isset($overrides['exclude']) && is_array($overrides['exclude'])
            ? $overrides['exclude']
            : $defaults->list('exclude');

        if (isset($appends['exclude']) && is_array($appends['exclude'])) {
            /** @var list<string> $extra */
            $extra = $appends['exclude'];
            $exclude = array_values(array_unique(array_merge($exclude, $extra)));
        }

        if (isset($appends['php.src']) && is_array($appends['php.src'])) {
            /** @var list<string> $extra */
            $extra = $appends['php.src'];
            $include = array_values(array_unique(array_merge($include, $extra)));
        }

        $this->include = $include;
        $this->exclude = $exclude;
    }

    /** @return list<string> */
    public function include(): array
    {
        return $this->include;
    }

    /** @return list<string> */
    public function exclude(): array
    {
        return $this->exclude;
    }
}
