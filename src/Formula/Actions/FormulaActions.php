<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Actions;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\Envs\Envs;
use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Action\EnvsAction;
use Haspadar\Piqule\Formula\Action\FirstAction;
use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Action\FormatEachAction;
use Haspadar\Piqule\Formula\Action\IfEmptyAction;
use Haspadar\Piqule\Formula\Action\IfNotEmptyAction;
use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Action\ReplaceAction;
use Haspadar\Piqule\Formula\Action\ShellQuoteAction;
use Haspadar\Piqule\PiquleException;

/**
 * Complete set of DSL action factories for placeholder resolution.
 *
 * Example:
 *
 *     (new FormulaActions($config, $envs))->map();
 */
final readonly class FormulaActions
{
    /**
     * Accepts context-specific dependencies for stateful actions.
     *
     * @param Config $config Configuration for config() action
     * @param Envs $envs Environment variables for envs() action
     */
    public function __construct(private Config $config, private Envs $envs) {}

    /**
     * Builds the complete action factory map for DSL placeholder resolution.
     *
     * @return array<string, callable(string): Action>
     */
    public function map(): array
    {
        return [
            'config' => fn(string $raw): Action => new ConfigAction($this->config, $raw),
            'envs' => fn(string $raw): Action => new EnvsAction($this->envs, $raw),
            'first' => static fn(string $raw): Action => match (trim($raw)) {
                '' => new FirstAction(),
                default => throw new PiquleException('Action "first" does not accept arguments'),
            },
            'format' => static fn(string $raw): Action => new FormatAction($raw),
            'format_each' => static fn(string $raw): Action => new FormatEachAction($raw),
            'if_empty' => static fn(string $raw): Action => match (trim($raw)) {
                '' => new IfEmptyAction(),
                default => throw new PiquleException('Action "if_empty" does not accept arguments'),
            },
            'if_not_empty' => static fn(string $raw): Action => match (trim($raw)) {
                '' => new IfNotEmptyAction(),
                default => throw new PiquleException('Action "if_not_empty" does not accept arguments'),
            },
            'join' => static fn(string $raw): Action => new JoinAction($raw),
            'replace' => static fn(string $raw): Action => new ReplaceAction($raw),
            'shell_quote' => static fn(string $raw): Action => match (trim($raw)) {
                '' => new ShellQuoteAction(),
                default => throw new PiquleException('Action "shell_quote" does not accept arguments'),
            },
        ];
    }
}
