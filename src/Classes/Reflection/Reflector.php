<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection;

interface Reflector
{
    /**
     * @param class-string $classFQN
     */
    public function reflectClass(string $classFQN): ReflectionClass;

    /**
     * @param class-string     $classFQN
     * @param non-empty-string $methodName
     */
    public function reflectMethod(string $classFQN, string $methodName): ReflectionMethod;
}
