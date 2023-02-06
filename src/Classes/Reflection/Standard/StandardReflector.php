<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection\Standard;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use PhpProject\SourceCode\Classes\Reflection\Reflector;

final readonly class StandardReflector implements Reflector
{
    /**
     * @param class-string $classFQN
     */
    public function reflectClass(string $classFQN): ReflectionClass
    {
        return StandardReflectionClass::createFromName($classFQN);
    }

    /**
     * @param class-string     $classFQN
     * @param non-empty-string $methodName
     */
    public function reflectMethod(string $classFQN, string $methodName): ReflectionMethod
    {
        return StandardReflectionMethod::createFromName($classFQN, $methodName);
    }
}
