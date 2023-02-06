<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection;

interface ReflectionMethod
{
    /**
     * @return non-empty-string
     */
    public function getShortName(): string;

    public function getClass(): ReflectionClass;

    public function getDocComment(): ?string;

    public function getBody(): string;

    public function isPublic(): bool;

    public function isProtected(): bool;

    public function isPrivate(): bool;

    /**
     * @param class-string $attributeClassName
     */
    public function hasAttribute(string $attributeClassName): bool;
}
