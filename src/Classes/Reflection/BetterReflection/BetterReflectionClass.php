<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection\BetterReflection;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod as BetterReflectionReflectionMethod;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;

final readonly class BetterReflectionClass implements ReflectionClass
{
    public function __construct(
        private BetterReflectionReflectionClass $reflectionClass
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
        $reflectionMethod = $this->reflectionClass->getMethod($methodName);
        if (!$reflectionMethod instanceof BetterReflectionReflectionMethod) {
            throw new \InvalidArgumentException(sprintf('Method %s not found in class %s', $methodName, $this->getName()));
        }

        return new BetterReflectionMethod($reflectionMethod);
    }

    /**
     * @return array<ReflectionMethod>
     */
    public function getMethods(): array
    {
        return array_map(
            static fn (BetterReflectionReflectionMethod $reflectionMethod): BetterReflectionMethod => new BetterReflectionMethod($reflectionMethod),
            $this->reflectionClass->getImmediateMethods()
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
            return new self(BetterReflectionReflectionClass::createFromName($FQCN));
        } catch (IdentifierNotFound $e) {
            throw new \InvalidArgumentException(sprintf('Class %s not found', $FQCN), $e->getCode(), $e);
        }
    }
}
