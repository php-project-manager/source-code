<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes;

use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;

final readonly class SourceMethod
{
    public function __construct(
        public MethodIdentity $identity,
        public string $docBlock,
        public string $body
    ) {
    }

    public function name(): string
    {
        return $this->identity->name;
    }

    public static function fromReflectionMethod(ReflectionMethod $reflectionMethod): self
    {
        $identity = MethodIdentity::fromReflectionMethod($reflectionMethod);

        return self::fromIdentity($identity);
    }

    public static function fromIdentity(MethodIdentity $identity): self
    {
        $reflectionMethod = $identity->reflectionMethod;

        return new self(
            $identity,
            $reflectionMethod->getDocComment() ?? '',
            $reflectionMethod->getBody()
        );
    }
}
