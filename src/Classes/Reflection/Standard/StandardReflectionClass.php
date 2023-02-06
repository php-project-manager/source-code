<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection\Standard;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;

final readonly class StandardReflectionClass implements ReflectionClass
{
    /**
     * @param \ReflectionClass<object> $reflectionClass
     */
    public function __construct(
        private \ReflectionClass $reflectionClass
    ) {
    }

    public function getName(): string
    {
        return $this->reflectionClass->getName();
    }

    public function getShortName(): string
    {
        return $this->reflectionClass->getShortName();
    }

    /**
     * @param non-empty-string $methodName
     */
    public function getMethod(string $methodName): ReflectionMethod
    {
        try {
            $reflectionMethod = $this->reflectionClass->getMethod($methodName);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException(sprintf('Method %s not found in class %s', $methodName, $this->getName()), $e->getCode(), $e);
        }

        return new StandardReflectionMethod($reflectionMethod);
    }

    /**
     * @return array<ReflectionMethod>
     */
    public function getMethods(): array
    {
        return array_map(
            static fn (\ReflectionMethod $reflectionMethod): StandardReflectionMethod => new StandardReflectionMethod($reflectionMethod),
            $this->reflectionClass->getMethods()
        );
    }

    public function implementsInterface(string $interfaceFQCN): bool
    {
        return $this->reflectionClass->implementsInterface($interfaceFQCN);
    }

    /**
     * @param class-string $FQCN
     */
    public static function createFromName(string $FQCN): self
    {
        try {
            return new self(new \ReflectionClass($FQCN));
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException(sprintf('Class %s not found', $FQCN), $e->getCode(), $e);
        }
    }
}
