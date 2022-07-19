<?php

declare(strict_types=1);

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