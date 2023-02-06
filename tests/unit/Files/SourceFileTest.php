<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Files;

use PhpProject\SourceCode\Files\Path\RelativePath;
use PhpProject\SourceCode\Files\SourceFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-file')] final class SourceFileTest extends TestCase
{
    #[Test]
    public function it_retrieves_the_short_name_and_path(): void
    {
        $path = RelativePath::raw('dir/myFile.ext');
        $file = SourceFile::fromPath($path);

        self::assertEquals('myFile.ext', $file->shortName);
        self::assertEquals('dir/myFile.ext', (string) $file->path);
    }

    public static function path(string $path): RelativePath
    {
        return RelativePath::raw($path);
    }
}
