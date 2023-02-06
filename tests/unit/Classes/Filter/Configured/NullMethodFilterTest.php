<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\Configured\NullMethodFilter;
use PhpProject\SourceCode\Classes\Filter\Filter;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('filter')]
#[Group('class-filter')]
#[Group('configured-filter')] final class NullMethodFilterTest extends TestCase
{
    protected function setUp(): void
    {
        Fixtures::load();
    }

    #[Test]
    public function it_always_matches(): void
    {
        $filter = new NullMethodFilter();

        self::assertTrue($filter->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(self::class, 'it_always_matches'))));
        self::assertTrue($filter->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesClass'))));
        self::assertTrue($filter->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(Filter::class, 'matchesMethod'))));
        self::assertTrue($filter->matchesMethod(MethodIdentity::fromReflectionMethod(StandardReflectionMethod::createFromName(\Stringable::class, '__toString'))));
    }
}
