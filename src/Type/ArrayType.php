<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class ArrayType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_array($value);
    }

    public function getType(): string
    {
        return 'array';
    }
}