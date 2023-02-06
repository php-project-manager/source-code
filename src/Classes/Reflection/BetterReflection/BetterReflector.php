<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection\BetterReflection;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use PhpProject\SourceCode\Classes\Reflection\Reflector;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use Roave\BetterReflection\Reflector\Reflector as BetterReflectionReflector;

final readonly class BetterReflector implements Reflector
{
    public function __construct(
        private BetterReflectionReflector $reflector
    ) {
    }

    /**
     * @param class-string $classFQN
     */
    public function reflectClass(string $classFQN): ReflectionClass
    {
        try {
            return new BetterReflectionClass(
                $this->reflector->reflectClass($classFQN)
            );
        } catch (IdentifierNotFound $e) {
            throw new \InvalidArgumentException(sprintf('Class %s not found', $classFQN), $e->getCode(), $e);
        }
    }

    /**
     * @param class-string     $classFQN
     * @param non-empty-string $methodName
     */
    public function reflectMethod(string $classFQN, string $methodName): ReflectionMethod
    {
        return $this->reflectClass($classFQN)->getMethod($methodName);
    }
}
