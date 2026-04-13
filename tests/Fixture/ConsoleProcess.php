<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fixture;

final readonly class ConsoleProcess
{
    private string $stdout;

    private string $stderr;

    public function __construct(string $method, string $text)
    {
        $script = sprintf(
            'require %s; (new \Haspadar\Piqule\Output\Console())->%s(%s);',
            escapeshellarg(dirname(__DIR__, 2) . '/vendor/autoload.php'),
            $method,
            var_export($text, true),
        );

        $proc = proc_open(
            ['php', '-r', $script],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
        );

        $this->stdout = (string) stream_get_contents($pipes[1]);
        $this->stderr = (string) stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);
    }

    public function stdout(): string
    {
        return $this->stdout;
    }

    public function stderr(): string
    {
        return $this->stderr;
    }
}
