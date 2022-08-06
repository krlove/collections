<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

class ArrayType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_array($value);
    }

    public function getName(): string
    {
        return 'array';
    }
}