<?php

namespace Krlove\Collection\Type;

class NullType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_null($value);
    }

    public function __toString()
    {
        return 'null';
    }
}