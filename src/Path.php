<?php

declare(strict_types=1);

namespace Uneaowu\Path;

final class Path
{
    private function __construct(
        private array $parts,
        private string $sep,
    ) {
    }

    public static function make(string $path, string ...$paths): self
    {
        $sep = DIRECTORY_SEPARATOR;

        $paths = [$path, ...array_values($paths)];

        $parts = [];

        foreach ($paths as $p) {
            $parts = array_merge($parts, self::resolveParts($p, $sep));
        }

        return new self(parts: $parts, sep: $sep);
    }

    private static function resolveParts(string $path, string $sep): array
    {
        $parts = explode($sep, $path);

        $parts = LazyIter::make($parts)
            ->map(static function (string $s) use ($sep): string {
                return trim($s, $sep);
            })
            ->filter(static function (string $s): bool {
                return '' !== $s;
            })
            ->values()
            ->all();

        if (str_starts_with($path, $sep)) {
            if (! empty($parts)) {
                $parts[0] = $sep.$parts[0];
            } else {
                $parts = [$sep];
            }
        }

        return $parts;
    }

    public function __toString(): string
    {
        if ($this->parts[0] === $this->sep) {
            return $this->parts[0].join($this->sep, array_slice($this->parts, 1));
        }

        return join($this->sep, $this->parts);
    }
}
