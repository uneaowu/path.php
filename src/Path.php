<?php

declare(strict_types=1);

namespace Uneaowu\Path;

final readonly class Path
{
    public const SEP = DIRECTORY_SEPARATOR;

    private function __construct(
        private array $parts,
    ) {
    }

    public static function make(string $path, string ...$paths): self
    {
        $paths = [$path, ...array_values($paths)];

        $parts = [];

        foreach ($paths as $p) {
            $parts = [...$parts, ...self::resolveParts($p)];
        }

        return new self(parts: $parts);
    }

    public function append(string $path): self
    {
        return new self([...$this->parts, ...self::resolveParts($path)]);
    }

    public function prepend(string $path): self
    {
        // simply ignore the prepend operation, when the path is absolute
        if ($this->isAbsolute()) {
            return clone $this;
        }

        $parts = [...self::resolveParts($path), ...$this->parts];

        return new self($parts);
    }

    private static function resolveParts(string $path): array
    {
        $parts = explode(self::SEP, $path);

        $parts = LazyIter::make($parts)
            ->map(static function (string $s): string {
                return trim($s, self::SEP);
            })
            ->filter(static function (string $s): bool {
                return '' !== $s;
            })
            ->values()
            ->all();

        if (str_starts_with($path, self::SEP)) {
            if (! empty($parts)) {
                $parts[0] = self::SEP.$parts[0];
            } else {
                $parts = [self::SEP];
            }
        }

        return $parts;
    }

    public function isAbsolute(): bool
    {
        return str_starts_with($this->parts[0], self::SEP);
    }

    public function isRelative(): bool
    {
        return ! $this->isAbsolute();
    }

    public function __toString(): string
    {
        if (self::SEP === $this->parts[0]) {
            return $this->parts[0].join(self::SEP, array_slice($this->parts, 1));
        }

        return join(self::SEP, $this->parts);
    }

    public function asParts(): array
    {
        return $this->parts;
    }
}
