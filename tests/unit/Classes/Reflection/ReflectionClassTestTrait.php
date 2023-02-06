<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PHPUnit\Framework\Attributes\Test;

trait ReflectionClassTestTrait
{
    /**
     * @param class-string $className
     */
    abstract protected function buildFromClassName(string $className): ReflectionClass;

    #[Test]
    public function it_is_built_from_a_class_name(): void
    {
        $reflectionClass = $this->buildFromClassName(ClassToReflect::class);

        self::assertEquals(ClassToReflect::class, $reflectionClass->getName());
        self::assertEquals('ClassToReflect', $reflectionClass->getShortName());
        self::assertTrue($reflectionClass->implementsInterface(\Stringable::class));

        self::assertCount(4, $reflectionClass->getMethods());
        self::assertEquals('publicMethod', $reflectionClass->getMethod('publicMethod')->getShortName());
        self::assertEquals('protectedMethod', $reflectionClass->getMethod('protectedMethod')->getShortName());
        self::assertEquals('privateMethod', $reflectionClass->getMethod('privateMethod')->getShortName());
        self::assertEquals('__toString', $reflectionClass->getMethod('__toString')->getShortName());
    }

    #[Test]
    public function it_cannot_be_built_from_a_non_existing_class_name(): void
    {
        /** @var class-string $nonExistingClassName */
        $nonExistingClassName = 'I\\Do\\Not\\Exist';

        $this->expectException(\InvalidArgumentException::class);
        $this->buildFromClassName($nonExistingClassName);
    }

    #[Test]
    public function it_cannot_get_non_existing_method(): void
    {
        $reflectionClass = $this->buildFromClassName(ClassToReflect::class);

        $this->expectException(\InvalidArgumentException::class);
        $reflectionClass->getMethod('unknown');
    }
}

/**
 * @internal
 */
class ClassToReflect implements \Stringable
{
    public function publicMethod(): void
    {
        $this->protectedMethod();
    }

    protected function protectedMethod(): void
    {
        $this->privateMethod();
    }

    private function privateMethod(): void
    {
    }

    public function __toString(): string
    {
        return '';
    }
}
