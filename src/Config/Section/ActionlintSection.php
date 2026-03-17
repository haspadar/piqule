<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for actionlint
 */
final readonly class ActionlintSection implements ConfigSection
{
    #[Override]
    public function toArray(): array
    {
        return [
            'actionlint.enabled' => true,
        ];
    }
}
