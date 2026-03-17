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
    ) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'phpcs.excludes' => $this->excludes,
            'phpcs.files' => $this->includes,
            'phpcs.enabled' => true,
        ];
    }
}
