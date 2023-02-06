<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Constraint;

use PhpProject\SourceCode\Files\SourceFile;

final readonly class CompoundFileConstraint implements FileConstraint
{
    /**
     * @var array<FileConstraint>
     */
    private array $constraints;

    public function __construct(FileConstraint ...$constraints)
    {
        $this->constraints = $constraints;
    }

    public function isSatisfiedBy(SourceFile $file): bool
    {
        return array_reduce(
            $this->constraints,
            static fn (bool $satisfies, FileConstraint $constraint): bool => $satisfies && $constraint->isSatisfiedBy($file),
            true
        );
    }
}
