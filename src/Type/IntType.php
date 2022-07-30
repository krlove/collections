<?php

declare(strict_types=1);

namespace Krlove\Collection\Type;

class IntType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_int($value);
    }

    public function getType(): string
    {
        return 'int';
    }
}