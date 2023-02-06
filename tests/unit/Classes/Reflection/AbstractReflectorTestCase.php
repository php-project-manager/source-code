<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use PhpProject\SourceCode\Classes\Reflection\Reflector;
use PHPUnit\Framework\TestCase;

abstract class AbstractReflectorTestCase extends TestCase
{
    use ReflectionClassTestTrait;
    use ReflectionMethodTestTrait;

    abstract protected function createReflector(): Reflector;

    /**
     * @param class-string $className
     */
    protected function buildFromClassName(string $className): ReflectionClass
    {
        return $this->createReflector()->reflectClass($className);
    }

    /**
     * @param class-string     $className
     * @param non-empty-string $methodName
     */
    protected function buildFromClassNameAndMethodName(string $className, string $methodName): ReflectionMethod
    {
        return $this->createReflector()->reflectMethod($className, $methodName);
    }
}
