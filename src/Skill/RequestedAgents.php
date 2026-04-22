<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Skill;

use Haspadar\Piqule\PiquleException;

/**
 * Resolves the list of agent targets requested via `--agent=<csv>`.
 */
final readonly class RequestedAgents
{
    private const string OPTION_PREFIX = '--agent=';

    /** Placeholder used when the option is absent from argv. */
    private const string MISSING = "\0missing";

    /**
     * Initializes with raw CLI arguments and the registry of supported targets.
     *
     * @param list<string> $argv Raw command-line arguments.
     * @param array<string, AgentTarget> $available Available targets keyed by name.
     */
    public function __construct(private array $argv, private array $available) {}

    /**
     * Returns the resolved target list in the order they are requested.
     *
     * @throws PiquleException when the option is missing, empty, or names an unknown agent.
     * @return list<AgentTarget>
     */
    public function targets(): array
    {
        $raw = $this->optionValue();

        if ($raw === self::MISSING) {
            throw new PiquleException('Missing required option: --agent=<name>[,<name>...]');
        }

        if (trim($raw) === '') {
            throw new PiquleException('Option --agent must not be empty');
        }

        $resolved = [];
        $seen = [];

        foreach (explode(',', $raw) as $part) {
            $name = trim($part);

            if ($name === '') {
                continue;
            }

            if (!array_key_exists($name, $this->available)) {
                throw new PiquleException(
                    sprintf(
                        'Unknown agent: "%s". Supported: %s',
                        $name,
                        implode(', ', array_keys($this->available)),
                    ),
                );
            }

            if (array_key_exists($name, $seen)) {
                continue;
            }

            $seen[$name] = true;
            $resolved[] = $this->available[$name];
        }

        if ($resolved === []) {
            throw new PiquleException('Option --agent must name at least one agent');
        }

        return $resolved;
    }

    /**
     * Returns the raw `--agent=` option value or the MISSING sentinel when the option is absent.
     */
    private function optionValue(): string
    {
        foreach ($this->argv as $argument) {
            if (str_starts_with($argument, self::OPTION_PREFIX)) {
                return substr($argument, strlen(self::OPTION_PREFIX));
            }
        }

        return self::MISSING;
    }
}
