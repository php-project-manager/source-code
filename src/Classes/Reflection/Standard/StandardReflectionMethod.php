<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection\Standard;

use Assert\Assert;
use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;

final readonly class StandardReflectionMethod implements ReflectionMethod
{
    public function __construct(
        private \ReflectionMethod $reflectionMethod
    ) {
    }

    public function getShortName(): string
    {
        /** @var non-empty-string $shortName */
        $shortName = $this->reflectionMethod->getShortName();
        Assert::that($shortName)->notEmpty();

        return $shortName;
    }

    public function getClass(): ReflectionClass
    {
        return new StandardReflectionClass($this->reflectionMethod->getDeclaringClass());
    }

    public function getDocComment(): ?string
    {
        $docComment = $this->reflectionMethod->getDocComment();

        if ($docComment === false) {
            return null;
        }

        $commentAsArray = explode(\PHP_EOL, $docComment);

        return implode(
            \PHP_EOL,
            array_map(
                static function (string $line): string {
                    $trimmedLine = trim($line);
                    if (str_starts_with($trimmedLine, '*')) {
                        return ' '.$trimmedLine;
                    }

                    return $trimmedLine;
                },
                $commentAsArray
            )
        );
    }

    public function getBody(): string
    {
        $fileName = $this->reflectionMethod->getDeclaringClass()->getFileName();
        Assert::that($fileName)->string();

        $fileContentAsArray = explode(\PHP_EOL, (string) file_get_contents($fileName));

        $startLine = (int) $this->reflectionMethod->getStartLine() - 1;
        $endLine   = (int) $this->reflectionMethod->getEndLine();

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
        return $this->reflectionMethod->getAttributes($attributeClassName, \ReflectionAttribute::IS_INSTANCEOF) !== [];
    }

    /**
     * @param class-string     $classFQN
     * @param non-empty-string $methodName
     */
    public static function createFromName(string $classFQN, string $methodName): ReflectionMethod
    {
        return StandardReflectionClass::createFromName($classFQN)->getMethod($methodName);
    }
}
