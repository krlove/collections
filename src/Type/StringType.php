<?php

namespace Krlove\Collection\Type;

class StringType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_string($value);
    }

    public function __toString()
    {
        return 'string';
    }
}