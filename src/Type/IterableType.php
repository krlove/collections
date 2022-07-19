<?php

namespace Krlove\Collection\Type;

class IterableType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_iterable($value);
    }

    public function __toString()
    {
        return 'iterable';
    }
}