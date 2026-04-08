<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Envs;

use Override;

/**
 * No environment variables configured.
 */
final readonly class EmptyEnvs implements Envs
{
    #[Override]
    public function vars(): array
    {
        return [];
    }
}
