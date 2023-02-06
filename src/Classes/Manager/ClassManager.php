<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Manager;

use PhpProject\SourceCode\Classes\Filter\Filter;
use PhpProject\SourceCode\Classes\SourceClass;
use PhpProject\SourceCode\Classes\SourceClasses;
use PhpProject\SourceCode\Files\SourceFile;
use PhpProject\SourceCode\Files\SourceFiles;

interface ClassManager
{
    public function getClass(SourceFile $file, Filter $filter = new Filter()): ?SourceClass;

    public function getClasses(SourceFiles $files, Filter $filter = new Filter()): SourceClasses;
}
