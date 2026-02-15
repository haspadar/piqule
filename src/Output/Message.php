<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output;

final readonly class Message
{
    public function __construct(private string $body) {}

    public function body(): string
    {
        return $this->body;
    }
}
