<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Skill;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Skill\AgentTarget;
use Haspadar\Piqule\Skill\RequestedAgents;
use Haspadar\Piqule\Tests\Fake\Skill\FakeAgentTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RequestedAgentsTest extends TestCase
{
    #[Test]
    public function resolvesSingleTargetFromAgentOption(): void
    {
        self::assertSame(
            ['sonnet.md'],
            array_map(
                fn(AgentTarget $target): string => $target->path(),
                (new RequestedAgents(
                    ['--agent=sonnet'],
                    ['sonnet' => new FakeAgentTarget('sonnet')],
                ))->targets(),
            ),
            'single --agent=<name> value must resolve to exactly that target',
        );
    }

    #[Test]
    public function resolvesMultipleTargetsFromCsvValue(): void
    {
        self::assertSame(
            ['violet.md', 'indigo.md'],
            array_map(
                fn(AgentTarget $target): string => $target->path(),
                (new RequestedAgents(
                    ['--agent=violet,indigo'],
                    [
                        'violet' => new FakeAgentTarget('violet'),
                        'indigo' => new FakeAgentTarget('indigo'),
                    ],
                ))->targets(),
            ),
            'csv value must resolve to all listed targets in order',
        );
    }

    #[Test]
    public function deduplicatesRepeatedNames(): void
    {
        self::assertCount(
            1,
            (new RequestedAgents(
                ['--agent=raven,raven'],
                ['raven' => new FakeAgentTarget('raven')],
            ))->targets(),
            'repeated names in csv must collapse to a single target',
        );
    }

    #[Test]
    public function failsWhenOptionIsAbsent(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessage('--agent');

        (new RequestedAgents(
            ['install'],
            ['whistle' => new FakeAgentTarget('whistle')],
        ))->targets();
    }

    #[Test]
    public function failsWhenOptionValueIsEmpty(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessage('empty');

        (new RequestedAgents(
            ['--agent='],
            ['amber' => new FakeAgentTarget('amber')],
        ))->targets();
    }

    #[Test]
    public function failsOnUnknownAgentName(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessage('mirage');

        (new RequestedAgents(
            ['--agent=mirage'],
            ['beacon' => new FakeAgentTarget('beacon')],
        ))->targets();
    }

    #[Test]
    public function failsWhenOptionValueContainsOnlySeparators(): void
    {
        $this->expectException(PiquleException::class);
        $this->expectExceptionMessage('at least one agent');

        (new RequestedAgents(
            ['--agent=,,'],
            ['cobalt' => new FakeAgentTarget('cobalt')],
        ))->targets();
    }
}
