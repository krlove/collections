<?php

declare(strict_types=1);

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