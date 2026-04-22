<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Skill;

use Haspadar\Piqule\Skill\AgentTarget;
use Override;

final readonly class FakeAgentTarget implements AgentTarget
{
    public function __construct(
        private string $name,
        private string $path = '',
        private string $marker = '<!-- fake -->',
    ) {}

    #[Override]
    public function path(): string
    {
        return $this->path === '' ? sprintf('%s.md', $this->name) : $this->path;
    }

    #[Override]
    public function wrapped(string $body): string
    {
        return $this->marker . "\n" . rtrim($body, "\n") . "\n" . $this->marker;
    }

    #[Override]
    public function markerPattern(): string
    {
        return '/' . preg_quote($this->marker, '/') . '.*?' . preg_quote($this->marker, '/') . '/s';
    }
}
