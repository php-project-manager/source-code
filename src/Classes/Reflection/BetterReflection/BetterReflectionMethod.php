<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection\BetterReflection;

use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionMethod as BetterReflectionReflectionMethod;

final readonly class BetterReflectionMethod implements ReflectionMethod
{
    public function __construct(
        private BetterReflectionReflectionMethod $reflectionMethod
    ) {
    }

    public function getShortName(): string
    {
        return $this->reflectionMethod->getShortName();
    }

    public function getClass(): ReflectionClass
    {
        return new BetterReflectionClass($this->reflectionMethod->getImplementingClass());
    }

    public function getDocComment(): ?string
    {
        return $this->reflectionMethod->getDocComment();
    }

    public function getBody(): string
    {
        $fileContentAsArray = explode(\PHP_EOL, $this->reflectionMethod->getLocatedSource()->getSource());

        $startLine = $this->reflectionMethod->getStartLine() - 1;
        $endLine   = $this->reflectionMethod->getEndLine();

        return implode(
            \PHP_EOL,
            \array_slice(
                $fileContentAsArray,
                $startLine,
                $endLine - $startLine
            )
        );
    }

    public function isPublic(): bool
    {
        return $this->reflectionMethod->isPublic();
    }

    public function isProtected(): bool
    {
        return $this->reflectionMethod->isProtected();
    }

    public function isPrivate(): bool
    {
        return $this->reflectionMethod->isPrivate();
    }

    public function hasAttribute(string $attributeClassName): bool
    {
        return $this->reflectionMethod->getAttributesByInstance($attributeClassName) !== [];
    }

    /**
     * @param class-string     $classFQN
     * @param non-empty-string $methodName
     */
    public static function createFromName(string $classFQN, string $methodName): ReflectionMethod
    {
        return BetterReflectionClass::createFromName($classFQN)->getMethod($methodName);
    }
}
