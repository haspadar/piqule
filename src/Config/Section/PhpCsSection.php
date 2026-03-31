<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for PHP_CodeSniffer
 */
final readonly class PhpCsSection implements ConfigSection
{
    /**
     * @param list<string> $includes
     * @param list<string> $excludes
     */
    public function __construct(
        private array $includes,
        private array $excludes,
        private string $rootNamespace = '',
    ) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'phpcs.excludes' => $this->excludes,
            'phpcs.files' => $this->includes,
            'phpcs.root_namespace' => $this->rootNamespace,
            'phpcs.enabled' => true,
        ];
    }
}
