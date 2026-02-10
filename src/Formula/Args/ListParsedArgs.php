<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use InvalidArgumentException;
use Override;

final readonly class ListParsedArgs implements Args
{
    public function __construct(private Args $origin) {}

    #[Override]
    public function text(): string
    {
        return $this->origin->text();
    }

    #[Override]
    public function list(): array
    {
        $raw = $this->origin->text();

        if ($raw === '' || $raw[0] !== '[' || $raw[strlen($raw) - 1] !== ']') {
            throw new InvalidArgumentException(
                sprintf('Expected php list literal, got "%s"', $raw),
            );
        }

        $inner = trim(substr($raw, 1, -1));

        if ($inner === '') {
            return [];
        }

        return explode(',', $inner);
    }
}
