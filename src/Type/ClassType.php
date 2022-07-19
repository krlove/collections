<?php

namespace Krlove\Collection\Type;

use Krlove\Collection\Exception\TypeException;

class ClassType extends AbstractType
{
    private string $class;

    public function __construct(string $class)
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw new TypeException(sprintf('Class or interface %s does not exist', $class));
        }

        $this->class = $class;
    }

    public function isTypeOf($value): bool
    {
        return $value instanceof $this->class;
    }

    public function __toString()
    {
        return $this->class;
    }
}