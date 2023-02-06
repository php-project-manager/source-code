<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Configured;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

final class Fixtures
{
    public static function load(): void
    {
    }
}

interface FirstInterface
{
    public function method(): void;
}

interface SecondInterface
{
    public function method(): void;
}

interface ThirdInterface
{
    public function otherMethod(): void;
}

final class FirstClass implements FirstInterface, ThirdInterface
{
    public function method(): void
    {
    }

    public function otherMethod(): void
    {
    }
}

final class SecondClass implements SecondInterface
{
    public function method(): void
    {
    }
}

final class PhpDocTest
{
    /**
     * @test
     */
    public function myTestMethod(): void
    {
    }

    #[Test]
    public function myAttributeTestMethod(): void
    {
    }

    /**
     * @tag
     */
    public function myTagMethod(): void
    {
    }

    #[Group('tag')]
    public function myAttributeTagMethod(): void
    {
    }

    /**
     * Main.
     */
    public function myMainMethod(): void
    {
    }
}
