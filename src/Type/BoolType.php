<?php

namespace Krlove\Collection\Type;

class BoolType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_bool($value);
    }

    public function __toString()
    {
        return 'bool';
    }
}