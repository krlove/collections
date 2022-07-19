<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class ArrayType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        return is_array($value);
    }

    public function __toString()
    {
        return 'array';
    }
}