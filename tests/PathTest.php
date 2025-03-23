<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Uneaowu\Path\LazyIter;
use Uneaowu\Path\Path;

#[CoversClass(LazyIter::class)]
#[CoversClass(Path::class)]
final class PathTest extends TestCase
{
    private const S = DIRECTORY_SEPARATOR;

    public function test_it_can_be_constructed_from_root(): void
    {
        $p = Path::make('/');
        $this->assertInstanceOf(Path::class, $p);

        $this->assertTrue($p->isAbsolute());
        $this->assertSame([self::S], $p->asParts());
        $this->assertSame(self::S, (string) $p);
    }

    public function test_it_builds_a_relative_path_from_parts(): void
    {
        $p = Path::make('foo', 'bar/', 'baz//');
        $this->assertInstanceOf(Path::class, $p);
        $this->assertTrue($p->isRelative());

        $parts = $p->asParts();
        $this->assertIsArray($parts);
        $this->assertSame(['foo', 'bar', 'baz'], $parts);
        $this->assertSame('foo'.self::S.'bar'.self::S.'baz', (string) $p);
    }

    public function test_it_builds_a_relative_path_from_mixed_arguments(): void
    {
        $p = Path::make('foo//bar///baz', 'foo2', 'bar2');
        $this->assertInstanceOf(Path::class, $p);
        $this->assertTrue($p->isRelative());

        $parts = $p->asParts();
        $this->assertIsArray($parts);
        $this->assertSame(['foo', 'bar', 'baz', 'foo2', 'bar2'], $parts);
        $this->assertSame('foo'.self::S.'bar'.self::S.'baz'.self::S.'foo2'.self::S.'bar2', (string) $p);
    }

    public function test_it_builds_an_absolute_path_from_parts(): void
    {
        $p = Path::make('/foo', 'bar', 'baz//');
        $this->assertInstanceOf(Path::class, $p);
        $this->assertTrue($p->isAbsolute());

        $parts = $p->asParts();
        $this->assertIsArray($parts);
        $this->assertSame(['/foo', 'bar', 'baz'], $parts);
        $this->assertSame('/foo'.self::S.'bar'.self::S.'baz', (string) $p);
    }

    public function test_it_builds_an_absolute_path_from_mixed_arguments(): void
    {
        $p = Path::make('/foo/////bar///baz///', 'foo2///', 'bar2/');
        $this->assertInstanceOf(Path::class, $p);
        $this->assertTrue($p->isAbsolute());

        $parts = $p->asParts();
        $this->assertIsArray($parts);
        $this->assertSame(['/foo', 'bar', 'baz', 'foo2', 'bar2'], $parts);
        $this->assertSame('/foo'.self::S.'bar'.self::S.'baz'.self::S.'foo2'.self::S.'bar2', (string) $p);
    }

    public function test_it_appends_a_path_to_a_relative_path(): void
    {
        $p1 = Path::make('foo/bar/baz');
        $this->assertInstanceOf(Path::class, $p1);
        $this->assertTrue($p1->isRelative());

        $p2 = $p1->append('bat');
        $this->assertInstanceOf(Path::class, $p1);
        $this->assertTrue($p1->isRelative());

        $this->assertNotSame($p1, $p2);

        $this->assertSame(['foo', 'bar', 'baz'], $p1->asParts());
        $this->assertSame(['foo', 'bar', 'baz', 'bat'], $p2->asParts());

        $this->assertSame('foo'.self::S.'bar'.self::S.'baz', (string) $p1);
        $this->assertSame('foo'.self::S.'bar'.self::S.'baz'.self::S.'bat', (string) $p2);
    }

    public function test_it_appends_a_path_to_an_absolute_path(): void
    {
        $p1 = Path::make('/foo/bar/baz');
        $this->assertInstanceOf(Path::class, $p1);
        $this->assertTrue($p1->isAbsolute());

        $p2 = $p1->append('bat');
        $this->assertInstanceOf(Path::class, $p1);
        $this->assertTrue($p2->isAbsolute());

        $this->assertNotSame($p1, $p2);

        $this->assertSame(['/foo', 'bar', 'baz'], $p1->asParts());
        $this->assertSame(['/foo', 'bar', 'baz', 'bat'], $p2->asParts());

        $this->assertSame('/foo'.self::S.'bar'.self::S.'baz', (string) $p1);
        $this->assertSame('/foo'.self::S.'bar'.self::S.'baz'.self::S.'bat', (string) $p2);
    }

    public function test_it_prepends_a_path_to_a_relative_path(): void
    {
        $p1 = Path::make('foo/bar/baz');
        $this->assertInstanceOf(Path::class, $p1);
        $this->assertTrue($p1->isRelative());

        $p2 = $p1->prepend('bat');
        $this->assertInstanceOf(Path::class, $p1);
        $this->assertTrue($p2->isRelative());

        $this->assertNotSame($p1, $p2);

        $this->assertSame(['foo', 'bar', 'baz'], $p1->asParts());
        $this->assertSame(['bat', 'foo', 'bar', 'baz'], $p2->asParts());

        $this->assertSame('foo'.self::S.'bar'.self::S.'baz', (string) $p1);
        $this->assertSame('bat'.self::S.'foo'.self::S.'bar'.self::S.'baz', (string) $p2);
    }
}
