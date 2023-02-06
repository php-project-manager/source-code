<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\Configured\NullClassFilter;
use PhpProject\SourceCode\Classes\Filter\Filter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('filter')]
#[Group('class-filter')]
#[Group('configured-filter')] final class NullClassFilterTest extends TestCase
{
    protected function setUp(): void
    {
        Fixtures::load();
    }

    #[Test]
    public function it_always_matches(): void
    {
        $filter = new NullClassFilter();

        self::assertTrue($filter->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(self::class))));
        self::assertTrue($filter->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(Filter::class))));
        self::assertTrue($filter->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(\Stringable::class))));
        self::assertTrue($filter->matchesClass(ClassIdentity::fromReflectionClass(StandardReflectionClass::createFromName(\ArrayAccess::class))));
    }
}
