<?php

declare(strict_types=1);

namespace Uneaowu\Path;

final readonly class LazyIter implements \IteratorAggregate
{
    private function __construct(
        private \Generator $gen,
    ) {
    }

    public static function make(iterable $items)
    {
        return self::withGenerator(static function () use ($items): \Generator {
            yield from $items;
        });
    }

    public function filter(callable $f): self
    {
        $gen = $this->gen;

        return self::withGenerator(static function () use ($gen, $f): \Generator {
            foreach ($gen as $k => $v) {
                if ($f($v, $k)) {
                    yield $k => $v;
                }
            }
        });
    }

    public function map(callable $m): self
    {
        $gen = $this->gen;

        return self::withGenerator(static function () use ($gen, $m): \Generator {
            foreach ($gen as $k => $v) {
                yield $k => $m($v, $k);
            }
        });
    }

    public function values(): self
    {
        $gen = $this->gen;

        return self::withGenerator(static function () use ($gen): \Generator {
            foreach ($gen as $v) {
                yield $v;
            }
        });
    }

    public function all(): array
    {
        return iterator_to_array($this->gen);
    }

    public function getIterator(): \Traversable
    {
        return $this->gen;
    }

    private static function withGenerator(callable $gen): self
    {
        return new self($gen());
    }
}
