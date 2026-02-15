<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output;

use Override;

final readonly class Console implements Output
{
    #[Override]
    public function info(string $text): void
    {
        fwrite(
            STDOUT,
            "\033[33m$text\033[0m" . PHP_EOL,
        );
    }

    #[Override]
    public function success(string $text): void
    {
        fwrite(
            STDOUT,
            "\033[32m$text\033[0m" . PHP_EOL,
        );
    }

    #[Override]
    public function error(string $text): void
    {
        fwrite(
            STDERR,
            "\033[31m$text\033[0m" . PHP_EOL,
        );
    }
}
