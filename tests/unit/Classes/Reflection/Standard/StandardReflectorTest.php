<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection\Standard;

use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflector;
use PhpProject\SourceCode\Tests\Classes\Reflection\AbstractReflectorTestCase;

final class StandardReflectorTest extends AbstractReflectorTestCase
{
    protected function createReflector(): StandardReflector
    {
        return new StandardReflector();
    }
}
