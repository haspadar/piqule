<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Fixtures;

use Haspadar\Piqule\PiquleException;

final readonly class DirectoryFixture
{
    private string $path;

    public function __construct(string $name)
    {
        $this->path = sprintf(
            '%s/%s_%s',
            sys_get_temp_dir(),
            $name,
            bin2hex(random_bytes(6)),
        );

        if (!mkdir($this->path, 0o755, true) && !is_dir($this->path)) {
            throw new PiquleException(
                sprintf(
                    'Failed to create test directory: "%s"',
                    $this->path,
                ),
            );
        }
    }

    public function withFile(string $relativePath, string $contents): self
    {
        $file = $this->path . '/' . $relativePath;
        $dir = dirname($file);

        if (!is_dir($dir) && !mkdir($dir, 0o755, true) && !is_dir($dir)) {
            throw new PiquleException(
                sprintf('Failed to create test directory: "%s"', $dir),
            );
        }

        if (file_put_contents($file, $contents) === false) {
            throw new PiquleException(
                sprintf('Failed to create test file: "%s"', $file),
            );
        }

        return $this;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function __destruct()
    {
        $this->removeDirectory($this->path);
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        if ($items === false) {
            throw new PiquleException(
                sprintf('Failed to scan test directory: "%s"', $dir),
            );
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;

            is_dir($path)
                ? $this->removeDirectory($path)
                : unlink($path);
        }

        rmdir($dir);
    }
}
