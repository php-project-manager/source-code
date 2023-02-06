<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Reflection\BetterReflection;

use PhpProject\SourceCode\Classes\Reflection\BetterReflection\BetterReflector;
use PhpProject\SourceCode\Tests\Classes\Reflection\AbstractReflectorTestCase;
use Roave\BetterReflection\BetterReflection;

final class BetterReflectorTest extends AbstractReflectorTestCase
{
    protected function createReflector(): BetterReflector
    {
        return new BetterReflector((new BetterReflection())->reflector());
    }
}
