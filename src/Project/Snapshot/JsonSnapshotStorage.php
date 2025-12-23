<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Snapshot;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Project\Hashes;
use JsonException;

final readonly class JsonSnapshotStorage implements SnapshotStorage
{
    public function __construct(
        private string $file,
    ) {}

    public function snapshot(): Snapshot
    {
        return new Snapshot(
            new Hashes($this->read()),
        );
    }

    /**
     * @throws JsonException
     */
    public function save(Snapshot $snapshot): void
    {
        $this->ensureDirectory();

        $json = json_encode(
            $snapshot->toArray(),
            JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES,
        );

        if (file_put_contents($this->file, $json) === false) {
            throw new PiquleException(
                sprintf('Failed to write snapshot file: %s', $this->file),
            );
        }
    }

    /**
     * @return array<string,string>
     */
    private function read(): array
    {
        if (!is_file($this->file)) {
            return [];
        }

        $content = file_get_contents($this->file);
        if ($content === false) {
            throw new PiquleException(
                sprintf('Cannot read snapshot file: %s', $this->file),
            );
        }

        try {
            /** @var array<string,string> */
            return json_decode($content, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new PiquleException(
                sprintf('Invalid snapshot JSON: %s', $this->file),
                previous: $e,
            );
        }
    }

    private function ensureDirectory(): void
    {
        $dir = dirname($this->file);

        if (!is_dir($dir)
            && !mkdir($dir, 0o755, true)
            && !is_dir($dir)
        ) {
            throw new PiquleException(
                sprintf('Cannot create snapshot directory: %s', $dir),
            );
        }
    }
}
