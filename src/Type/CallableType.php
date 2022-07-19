<?php

namespace Krlove\Collection\Type;

class CallableType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_callable($value);
    }

    public function __toString()
    {
        return 'callable';
    }
}