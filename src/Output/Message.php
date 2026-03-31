<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Output;

/**
 * An immutable text message with a plain string body
 */
final readonly class Message
{
    public function __construct(private string $body) {}

    public function body(): string
    {
        return $this->body;
    }
}
