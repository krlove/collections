<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

use Krlove\Collections\Exception\TypeException;

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
        return $value instanceof $this->class;
    }

    public function getName(): string
    {
        return $this->class;
    }
}
