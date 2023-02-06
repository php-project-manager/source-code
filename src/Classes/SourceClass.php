<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes;

use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Files\SourceFile;

final readonly class SourceClass
{
    private function __construct(
        public ClassIdentity $identity,
        public SourceFile $file,
        public SourceMethods $methods
    ) {
    }

    public function FQCN(): string
    {
        return $this->identity->fqcn;
    }

    public function shortName(): string
    {
        return $this->identity->shortName;
    }

    public static function build(
        ClassIdentity $classIdentity,
        SourceFile $file,
        SourceMethods $methods
    ): self {
        return new self(
            $classIdentity,
            $file,
            $methods
        );
    }
}
