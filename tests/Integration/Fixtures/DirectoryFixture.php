<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Fixtures;

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

        mkdir($this->path, recursive: true);
    }

    public function withFile(string $relativePath, string $contents): self
    {
        $fullPath = $this->path . '/' . $relativePath;
        $dir = dirname($fullPath);

        if (!is_dir($dir)) {
            mkdir($dir, recursive: true);
        }

        file_put_contents($fullPath, $contents);

        return $this;
    }

    public function path(): string
    {
        return $this->path;
    }
}
