<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Files\Path;

use PhpProject\SourceCode\Files\Path\PathUtil;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-file')]
#[Group('file-path')] final class PathUtilTest extends TestCase
{
    #[Test]
    public function it_cleans_the_path(): void
    {
        self::assertEquals('/', PathUtil::cleanDirectorySeparators('/'));
        self::assertEquals('/', PathUtil::cleanDirectorySeparators('/.'));
        self::assertEquals('', PathUtil::cleanDirectorySeparators('./'));
        self::assertEquals('', PathUtil::cleanDirectorySeparators('.'));
        self::assertEquals('/foo/bar', PathUtil::cleanDirectorySeparators('/foo/bar'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('foo/bar'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('foo/bar/'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('foo//bar'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('./foo/bar'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('foo/./bar'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('foo/./bar/.'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('foo/./bar/./'));
        self::assertEquals('foo/bar', PathUtil::cleanDirectorySeparators('./././foo/./////././bar/./.'));
    }
}
