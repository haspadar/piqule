<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Output;

use Haspadar\Piqule\Output\Output;

final class FakeOutput implements Output
{
    /** @var list<string> */
    private array $infos = [];

    /** @var list<string> */
    private array $successes = [];

    /** @var list<string> */
    private array $errors = [];

    public function info(string $text): void
    {
        $this->infos[] = $text;
    }

    public function success(string $text): void
    {
        $this->successes[] = $text;
    }

    public function error(string $text): void
    {
        $this->errors[] = $text;
    }

    /** @return list<string> */
    public function infos(): array
    {
        return $this->infos;
    }

    /** @return list<string> */
    public function successes(): array
    {
        return $this->successes;
    }

    /** @return list<string> */
    public function errors(): array
    {
        return $this->errors;
    }
}
