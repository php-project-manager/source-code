<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes;

use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionClass;
use PhpProject\SourceCode\Classes\SourceClass;
use PhpProject\SourceCode\Classes\SourceMethods;
use PhpProject\SourceCode\Files\Path\RelativePath;
use PhpProject\SourceCode\Files\SourceFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')] final class SourceClassTest extends TestCase
{
    #[Test]
    public function it_builds_from_identity_and_file_and_methods(): void
    {
        $reflectionClass = StandardReflectionClass::createFromName(SourceTestClass::class);
        $identity        = ClassIdentity::fromReflectionClass($reflectionClass);
        $file            = SourceFile::fromPath(RelativePath::raw('SourceClassTest.php'));
        $methods         = new SourceMethods([]);

        $sourceClass = SourceClass::build($identity, $file, $methods);

        self::assertEquals(SourceTestClass::class, $sourceClass->FQCN());
        self::assertEquals('SourceTestClass', $sourceClass->shortName());
        self::assertEquals($identity, $sourceClass->identity);
        self::assertEquals($file, $sourceClass->file);
        self::assertEquals($methods, $sourceClass->methods);
    }
}

final class SourceTestClass
{
}
