<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Envs\EmptyEnvs;
use Haspadar\Piqule\Formula\Action\EnvsAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsValues;
use Haspadar\Piqule\Tests\Fake\Envs\FakeEnvs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EnvsActionTest extends TestCase
{
    #[Test]
    public function rendersStepWithSingleVariable(): void
    {
        self::assertThat(
            (new EnvsAction(
                new FakeEnvs(['MY_VAR' => 'echo hello']),
                '"      "',
            ))->transformed(new ListArgs([])),
            new HasArgsValues([
                "      - name: Set environment variables\n"
                . "        run: |\n"
                . "          git fetch --tags\n"
                . '          echo "MY_VAR=$(echo hello)" >> "$GITHUB_ENV"',
            ]),
            'EnvsAction must render a step that exports one variable',
        );
    }

    #[Test]
    public function rendersStepWithMultipleVariables(): void
    {
        self::assertThat(
            (new EnvsAction(
                new FakeEnvs(['A' => 'cmd-a', 'B' => 'cmd-b']),
                '"      "',
            ))->transformed(new ListArgs([])),
            new HasArgsValues([
                "      - name: Set environment variables\n"
                . "        run: |\n"
                . "          git fetch --tags\n"
                . "          echo \"A=\$(cmd-a)\" >> \"\$GITHUB_ENV\"\n"
                . '          echo "B=$(cmd-b)" >> "$GITHUB_ENV"',
            ]),
            'EnvsAction must render a step that exports multiple variables',
        );
    }

    #[Test]
    public function rendersEmptyStringWhenNoVariables(): void
    {
        self::assertThat(
            (new EnvsAction(
                new EmptyEnvs(),
                '"      "',
            ))->transformed(new ListArgs([])),
            new HasArgsValues(['']),
            'EnvsAction must return empty string when no env vars are configured',
        );
    }
}
