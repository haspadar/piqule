<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use LogicException;
use Override;

final readonly class RawArgs implements Args
{
    public function __construct(private string $raw) {}

    #[Override]
    public function text(): string
    {
        return $this->raw;
    }

    #[Override]
    public function list(): array
    {
        throw new LogicException('RawArgs does not support list parsing');
    }
}
