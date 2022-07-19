<?php

namespace Krlove\Collection\Type;

class IntType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_int($value);
    }

    public function __toString()
    {
        return 'int';
    }
}