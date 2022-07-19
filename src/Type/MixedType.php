<?php

namespace Krlove\Collection\Type;

class MixedType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return true;
    }

    public function __toString()
    {
        return 'mixed';
    }
}
