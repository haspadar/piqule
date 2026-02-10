<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

use Override;

final readonly class UnquotedArgs implements Args
{
    public function __construct(private Args $origin) {}

    #[Override]
    public function text(): string
    {
        return $this->trim(
            $this->origin->text(),
        );
    }

    #[Override]
    public function list(): array
    {
        return array_map(
            fn(string $value) => $this->trim($value),
            $this->origin->list(),
        );
    }

    private function trim(string $text): string
    {
        return trim($text, "\"'");
    }
}
