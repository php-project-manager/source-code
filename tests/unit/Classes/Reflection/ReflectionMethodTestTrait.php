<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection;

use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use PHPUnit\Framework\Attributes\Test;

trait ReflectionMethodTestTrait
{
    /**
     * @param class-string     $className
     * @param non-empty-string $methodName
     */
    abstract protected function buildFromClassNameAndMethodName(string $className, string $methodName): ReflectionMethod;

    #[Test]
    public function it_is_built_from_a_class_name_and_public_method_name(): void
    {
        $reflectionMethod = $this->buildFromClassNameAndMethodName(ClassForMethodToReflect::class, 'publicMethod');

        self::assertEquals('publicMethod', $reflectionMethod->getShortName());

        self::assertEquals(ClassForMethodToReflect::class, $reflectionMethod->getClass()->getName());

        self::assertEquals('/**'.\PHP_EOL.' * @internal public method'.\PHP_EOL.' */', $reflectionMethod->getDocComment());

        self::assertEquals('    public function publicMethod(): void'.\PHP_EOL.'    {'.\PHP_EOL.'        $this->protectedMethod();'.\PHP_EOL.'    }', $reflectionMethod->getBody());

        self::assertTrue($reflectionMethod->isPublic());
        self::assertFalse($reflectionMethod->isProtected());
        self::assertFalse($reflectionMethod->isPrivate());
    }

    #[Test]
    public function it_is_built_from_a_class_name_and_protected_method_name(): void
    {
        $reflectionMethod = $this->buildFromClassNameAndMethodName(ClassForMethodToReflect::class, 'protectedMethod');

        self::assertEquals('protectedMethod', $reflectionMethod->getShortName());

        self::assertEquals(ClassForMethodToReflect::class, $reflectionMethod->getClass()->getName());

        self::assertEquals('/**'.\PHP_EOL.' * @internal protected method'.\PHP_EOL.' */', $reflectionMethod->getDocComment());

        self::assertEquals('    protected function protectedMethod(): void'.\PHP_EOL.'    {'.\PHP_EOL.'        $this->privateMethod();'.\PHP_EOL.'    }', $reflectionMethod->getBody());

        self::assertFalse($reflectionMethod->isPublic());
        self::assertTrue($reflectionMethod->isProtected());
        self::assertFalse($reflectionMethod->isPrivate());
    }

    #[Test]
    public function it_is_built_from_a_class_name_and_private_method_name(): void
    {
        $reflectionMethod = $this->buildFromClassNameAndMethodName(ClassForMethodToReflect::class, 'privateMethod');

        self::assertEquals('privateMethod', $reflectionMethod->getShortName());

        self::assertEquals(ClassForMethodToReflect::class, $reflectionMethod->getClass()->getName());

        self::assertEquals('/**'.\PHP_EOL.' * @internal private method'.\PHP_EOL.' */', $reflectionMethod->getDocComment());

        self::assertEquals('    private function privateMethod(): void'.\PHP_EOL.'    {'.\PHP_EOL.'        // Nothing to do'.\PHP_EOL.'    }', $reflectionMethod->getBody());

        self::assertFalse($reflectionMethod->isPublic());
        self::assertFalse($reflectionMethod->isProtected());
        self::assertTrue($reflectionMethod->isPrivate());
    }
}

/**
 * @internal
 */
class ClassForMethodToReflect
{
    /**
     * @internal public method
     */
    public function publicMethod(): void
    {
        $this->protectedMethod();
    }

    /**
     * @internal protected method
     */
    protected function protectedMethod(): void
    {
        $this->privateMethod();
    }

    /**
     * @internal private method
     */
    private function privateMethod(): void
    {
        // Nothing to do
    }
}
