<?php

declare(strict_types=1);

namespace Krlove\Collections\Type;

class IntType extends AbstractType
{
    public function isTypeOf($value): bool
    {
        if ($this->isNullable() && is_null($value)) {
            return true;
        }

        return is_int($value);
    }

    public function getName(): string
    {
        return 'int';
    }
}