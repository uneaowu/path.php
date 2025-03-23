<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Uneaowu\Template\Example;

#[CoversClass(Example::class)]
final class ExampleTest extends TestCase
{
    public function testCase(): void
    {
        $example = (new Example());
        $example->run();

        $this->assertTrue(true);
    }
}
