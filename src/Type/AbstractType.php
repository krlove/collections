<?php

namespace Krlove\Collection\Type;

use Krlove\Collection\Exception\TypeException;

abstract class AbstractType implements TypeInterface
{
    public function assertIsTypeOf($value): void
    {
        if (!$this->isTypeOf($value)) {
            throw new TypeException(sprintf('Variable must be of type %s', $this));
        }
    }
}