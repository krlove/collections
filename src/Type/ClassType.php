<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

use Krlove\Collections\Exception\TypeException;
use function class_exists;
use function interface_exists;
use function is_null;
use function sprintf;

class ClassType extends AbstractType
{
    private string $class;

    public function __construct(string $class, bool $nullable = false)
    {
        parent::__construct($nullable);

        if (!class_exists($class) && !interface_exists($class)) {
            throw new TypeException(sprintf('Class or interface %s does not exist', $class));
        }

        $this->class = $class;
    }

    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return $value instanceof $this->class;
    }

    public function getName(): string
    {
        return $this->class;
    }
}
