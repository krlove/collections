<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class FloatType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_float($value);
    }

    public function __toString()
    {
        return 'float';
    }
}