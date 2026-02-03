<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

namespace Haspadar\Piqule\File;

final readonly class ReplacedFile implements File
{
    public function __construct(
        private File $origin,
        private string $search,
        private string $replace,
    ) {}

    #[\Override]
    public function name(): string
    {
        return $this->origin->name();
    }

    #[\Override]
    public function contents(): string
    {
        return str_replace(
            $this->search,
            $this->replace,
            $this->origin->contents(),
        );
    }
}
