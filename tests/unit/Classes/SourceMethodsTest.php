<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes;

use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionMethod;
use PhpProject\SourceCode\Classes\SourceMethod;
use PhpProject\SourceCode\Classes\SourceMethods;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')] final class SourceMethodsTest extends TestCase
{
    #[Test]
    public function it_can_be_counted_and_crawled_and_returned_as_an_array(): void
    {
        $methodOne = $this->given_a_source_method_named('methodOne');
        $methodTwo = $this->given_a_source_method_named('methodTwo');

        $methods = new SourceMethods([$methodOne, $methodTwo]);

        self::assertCount(2, $methods);
        self::assertEquals([$methodOne, $methodTwo], $methods->asArray());

        $i = 0;
        foreach ($methods as $method) {
            self::assertEquals('/**'.\PHP_EOL.' * My comment.'.\PHP_EOL.' */', $method->docBlock);
            ++$i;
        }
        self::assertEquals(2, $i);
    }

    /**
     * @param non-empty-string $methodName
     */
    private function given_a_source_method_named(string $methodName): SourceMethod
    {
        $reflectionMethod = StandardReflectionMethod::createFromName(DummySourceClass::class, $methodName);

        return SourceMethod::fromReflectionMethod($reflectionMethod);
    }
}

final class DummySourceClass
{
    /**
     * My comment.
     */
    public function methodOne(): void
    {
        // My body
    }

    /**
     * My comment.
     */
    public function methodTwo(): void
    {
        // My body
    }
}
